<?php

namespace SimpleSAML\Module\sildisco;

use Sil\SspUtils\AnnouncementUtils;
use Sil\SspUtils\DiscoUtils;
use Sil\SspUtils\Metadata;
use SimpleSAML\Utils\Arrays;
use SimpleSAML\Utils\HTTP;

/**
 * This class implements a custom IdP discovery service, for use with a ssp hub (proxy)
 *
 * This module extends the basic IdP disco handler.
 *
 * @author Steve Bagwell SIL GTIS
 * @package SimpleSAMLphp
 */
class IdPDisco extends \SimpleSAML\XHTML\IdPDisco
{

    /* The session type for this class */
    public static string $sessionType = 'sildisco:authentication';

    /* The session key for checking if the current user has the beta_tester cookie */
    public static string $betaTesterSessionKey = 'beta_tester';

    /* The idp metadata key that says whether an IDP is betaEnabled */
    public static string $betaEnabledMdKey = 'betaEnabled';

    /* The idp metadata key that says whether an IDP is enabled */
    public static string $enabledMdKey = 'enabled';

    /* The sp metadata key that gives the name of the SP */
    public static string $spNameMdKey = 'name';

    /* Used to get the SP Entity ID, e.g. $spEntityId = $this->session->getData($sessionDataType, $sessionKeyForSP); */
    public static string $sessionDataType = 'sildisco:authentication';
    public static string $sessionKeyForSP = 'spentityid';


    /**
     * @inheritDoc
     */
    protected function log(string $message): void
    {
        \SimpleSAML\Logger::info('SildiscoIdPDisco.' . $this->instance . ': ' . $message);
    }

    /* Path to the folder with the SP and IdP metadata */
    private function getMetadataPath()
    {
        return __DIR__ . '/../../../metadata/';
    }

    private function getSPEntityIDAndReducedIdpList(): array
    {

        $idpList = $this->getIdPList();
        $idpList = $this->filterList($idpList);

        $spEntityId = $this->session->getData(self::$sessionDataType, self::$sessionKeyForSP);

        $idpList = DiscoUtils::getReducedIdpList(
            $idpList,
            $this->getMetadataPath(),
            $spEntityId
        );

        return array($spEntityId, self::enableBetaEnabled($idpList));
    }

    /**
     * @inheritDoc
     */
    public function handleRequest(): void
    {

        $this->start();
        list($spEntityId, $idpList) = $this->getSPEntityIDAndReducedIdpList();

        $httpUtils = new HTTP();

        if (sizeof($idpList) == 1) {
            $idp = array_keys($idpList)[0];
            $idp = $this->validateIdP($idp);
            if ($idp !== null) {

                $this->log(
                    'Choice made [' . $idp . '] (Redirecting the user back. returnIDParam=' .
                    $this->returnIdParam . ')'
                );

                $httpUtils->redirectTrustedURL(
                    $this->returnURL,
                    array($this->returnIdParam => $idp)
                );
            }
        }

        // Get the SP's name
        $spEntries = Metadata::getSpMetadataEntries($this->getMetadataPath());

        $t = new \SimpleSAML\XHTML\Template($this->config, 'selectidp-links', 'disco');

        $spName = null;

        $rawSPName = $spEntries[$spEntityId][self::$spNameMdKey] ?? null;
        if ($rawSPName !== null) {
            $arrayUtils = new Arrays();
            $spName = htmlspecialchars($t->getTranslator()->getPreferredTranslation(
                $arrayUtils->arrayize($rawSPName, 'en')
            ));
        }

        // in order to bypass some built-in simplesaml behavior, an extra idp
        // might've been added.  It's not meant to be displayed.
        unset($idpList['dummy']);

        $enabledIdps = [];
        foreach ($idpList as $idp) {
            if ($idp['enabled'] === true) {
                $enabledIdps[] = $idp;
            } else {
                $disabledIdps[] = $idp;
            }
        }

        $t->data['enabledIdps'] = $enabledIdps;
        $t->data['disabledIdps'] = $disabledIdps;
        $t->data['return'] = $this->returnURL;
        $t->data['returnIDParam'] = $this->returnIdParam;
        $t->data['entityID'] = $this->spEntityId;
        $t->data['spName'] = $spName;
        $t->data['urlpattern'] = htmlspecialchars($httpUtils->getSelfURLNoQuery());
        $t->data['announcement'] = AnnouncementUtils::getAnnouncement();
        $t->data['helpCenterUrl'] = $this->config->getValue('helpCenterUrl', '');

        $t->show();
    }

    /**
     * @param array $idpList the IDPs with their metadata
     * @param bool|null $isBetaTester optional (default=null) just for unit testing
     * @return array $idpList
     *
     * If the current user has the beta_tester cookie, then for each IDP in
     * the idpList that has 'betaEnabled' => true, give it 'enabled' => true
     *
     */
    public static function enableBetaEnabled(array $idpList, ?bool $isBetaTester = null): array
    {

        if ($isBetaTester === null) {
            $session = \SimpleSAML\Session::getSessionFromRequest();
            $isBetaTester = $session->getData(
                self::$sessionType,
                self::$betaTesterSessionKey
            );
        }

        if (!$isBetaTester) {
            return $idpList;
        }

        foreach ($idpList as $idp => $idpMetadata) {
            if (!empty($idpMetadata[self::$betaEnabledMdKey])) {
                $idpMetadata[self::$enabledMdKey] = true;
                $idpList[$idp] = $idpMetadata;
            }
        }

        return $idpList;
    }

    /**
     * @inheritDoc
     */
    protected function validateIdP(?string $idp): ?string
    {
        if ($idp === null) {
            return null;
        }
        if (!$this->config->getBoolean('idpdisco.validate', true)) {
            return $idp;
        }

        list($spEntityId, $idpList) = $this->getSPEntityIDAndReducedIdpList();

        /*
         * All this complication is for security.
         * Without it a user is able to use his authentication through an
         * IdP to login to an SP that normally shouldn't accept that IdP.
         *
         * With a good process, the current SP's entity ID will appear in the
         * session and in the request's 'return' entry.
         *
         * With a hacked process, the SP in the session will not appear in the
         * request's 'return' entry.
         */
        $returnKey = 'return';
        $requestReturn = array_key_exists($returnKey, $_REQUEST) ?
            urldecode(urldecode($_REQUEST[$returnKey])) : "";

        $spEntityIdParam = 'spentityid=' . $spEntityId;

        if (strpos($requestReturn, $spEntityIdParam) === false) {
            $message = 'Invalid SP entity id [' . $spEntityId . ']. ' .
                'Could not find in return value. ' . PHP_EOL . $requestReturn;
            $this->log($message);
            return null;
        }

        if (array_key_exists($idp, $idpList) && $idpList[$idp]['enabled']) {
            return $idp;
        }
        $this->log('Invalid IdP entity id [' . $idp . '] received from discovery page.');
        // the entity id wasn't valid
        return null;
    }
}
