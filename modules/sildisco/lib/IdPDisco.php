<?php

namespace SimpleSAML\Module\sildisco;

use Sil\SspUtils\AnnouncementUtils;
use Sil\SspUtils\DiscoUtils;
use Sil\SspUtils\Metadata;

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
    public static $sessionType = 'sildisco:authentication';

    /* The session key for checking if the current user has the beta_tester cookie */
    public static $betaTesterSessionKey = 'beta_tester';

    /* The idp metadata key that says whether an IDP is betaEnabled */
    public static $betaEnabledMdKey = 'betaEnabled';

    /* The idp metadata key that says whether an IDP is enabled */
    public static $enabledMdKey = 'enabled';

    /* The sp metadata key that gives the name of the SP */
    public static $spNameMdKey = 'name';

    /* Used to get the SP Entity ID, e.g. $spEntityId = $this->session->getData($sessionDataType, $sessionKeyForSP); */
    public static $sessionDataType = 'sildisco:authentication';
    public static $sessionKeyForSP = 'spentityid';


    /**
     * Log a message.
     *
     * This is an helper function for logging messages. It will prefix the messages with our discovery service type.
     *
     * @param string $message The message which should be logged.
     */
    protected function log($message)
    {
        \SimpleSAML\Logger::info('SildiscoIdPDisco.'.$this->instance.': '.$message);
    }

    /* Path to the folder with the SP and IdP metadata */
    private function getMetadataPath() {
        return __DIR__ . '/../../../metadata/';
    }

    private function getSPEntityIDAndReducedIdpList()
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
     * Handles a request to this discovery service.
     *
     * The IdP disco parameters should be set before calling this function.
     */
    public function handleRequest()
    {

        $this->start();
        list($spEntityId, $idpList) = $this->getSPEntityIDAndReducedIdpList();

        if (sizeof($idpList) == 1) {
            $idp = array_keys($idpList)[0];
            $idp = $this->validateIdP($idp);
            if ($idp !== null) {

                $this->log(
                    'Choice made [' . $idp . '] (Redirecting the user back. returnIDParam=' .
                    $this->returnIdParam . ')'
                );

                \SimpleSAML\Utils\HTTP::redirectTrustedURL(
                    $this->returnURL,
                    array($this->returnIdParam => $idp)
                );
            }
        }

        // Get the SP's name
        $spEntries = Metadata::getSpMetadataEntries($this->getMetadataPath());

        $t = new \SimpleSAML\XHTML\Template($this->config, 'selectidp-links.php', 'disco');

        $spName = null;

        $rawSPName = $spEntries[$spEntityId][self::$spNameMdKey] ?? null;
        if ($rawSPName !== null) {
            $spName = htmlspecialchars($t->getTranslator()->getPreferredTranslation(
                \SimpleSAML\Utils\Arrays::arrayize($rawSPName, 'en')
            ))   ;
        }

        $t->data['idplist'] = $idpList;
        $t->data['return'] = $this->returnURL;
        $t->data['returnIDParam'] = $this->returnIdParam;
        $t->data['entityID'] = $this->spEntityId;
        $t->data['spName'] = $spName;
        $t->data['urlpattern'] = htmlspecialchars(\SimpleSAML\Utils\HTTP::getSelfURLNoQuery());
        $t->data['announcement'] = AnnouncementUtils::getSimpleAnnouncement();
        $t->data['helpCenterUrl'] = $this->config->getValue('helpCenterUrl', '');

        $t->show();
    }

    /**
     * @param array $idpList the IDPs with their metadata
     * @param bool $isBetaTester optional (default=null) just for unit testing
     * @return array $idpList
     *
     * If the current user has the beta_tester cookie, then for each IDP in
     * the idpList that has 'betaEnabled' => true, give it 'enabled' => true
     *
     */
    public static function enableBetaEnabled($idpList, $isBetaTester=null) {

        if ( $isBetaTester === null) {
            $session = \SimpleSAML\Session::getSessionFromRequest();
            $isBetaTester = $session->getData(
                self::$sessionType,
                self::$betaTesterSessionKey
            );
        }

        if ( ! $isBetaTester) {
            return $idpList;
        }

        foreach ($idpList as $idp => $idpMetadata) {
            if ( ! empty($idpMetadata[self::$betaEnabledMdKey])) {
                $idpMetadata[self::$enabledMdKey] = true;
                $idpList[$idp] = $idpMetadata;
            }
        }

        return $idpList;
    }

    /**
     * Validates the given IdP entity id.
     *
     * Takes a string with the IdP entity id, and returns the entity id if it is valid, or
     * null if not. Ensures that the selected IdP is allowed for the current SP
     *
     * @param string|null $idp The entity id we want to validate. This can be null, in which case we will return null.
     *
     * @return string|null The entity id if it is valid, null if not.
     */
    protected function validateIdP($idp)
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
        $requestReturn =  array_key_exists($returnKey, $_REQUEST) ?
            urldecode(urldecode($_REQUEST[$returnKey])) : "";

        $spEntityIdParam = 'spentityid='.$spEntityId;

        if (strpos($requestReturn, $spEntityIdParam) === false) {
            $message = 'Invalid SP entity id [' . $spEntityId . ']. ' .
                'Could not find in return value. ' . PHP_EOL . $requestReturn;
            $this->log($message);
            return null;
        }


        if (array_key_exists($idp, $idpList) && $idpList[$idp]['enabled']) {
            return $idp;
        }
        $this->log('Invalid IdP entity id ['.$idp.'] received from discovery page.');
        // the entity id wasn't valid
        return null;
    }
}
