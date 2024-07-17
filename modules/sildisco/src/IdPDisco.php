<?php

namespace SimpleSAML\Module\sildisco;

use Sil\SspUtils\AnnouncementUtils;
use Sil\SspUtils\DiscoUtils;
use Sil\SspUtils\Metadata;
use SimpleSAML\Auth;
use SimpleSAML\Logger;
use SimpleSAML\Utils\HTTP;
use SimpleSAML\XHTML\IdPDisco as SSPIdPDisco;
use SimpleSAML\XHTML\Template;

/**
 * This class implements a custom IdP discovery service, for use with a ssp hub (proxy)
 *
 * This module extends the basic IdP disco handler.
 *
 * @author Steve Bagwell SIL GTIS
 * @package SimpleSAMLphp
 */
class IdPDisco extends SSPIdPDisco
{

    /* The session type for this class */
    public static string $sessionType = 'sildisco:authentication';

    /**
     * @inheritDoc
     */
    protected function log(string $message): void
    {
        Logger::info('SildiscoIdPDisco.' . $this->instance . ': ' . $message);
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

        // Creative solution for getting the EntityID from the SPMetadata entry in the state
        // Source: https://github.com/simplesamlphp/simplesamlphp-module-discopower/blob/5e2e5e9da751104d1553d273cfb2d0bd1e2b57df/src/PowerIdPDisco.php#L231
        // Before the SimpleSAMLphp 2 upgrade, we added it to the state ourselves by overriding the SAML2.php file
        parse_str(parse_url($_GET['return'], PHP_URL_QUERY), $returnState);
        $state = Auth\State::loadState($returnState['AuthID'], 'saml:sp:sso');
        if ($state && array_key_exists('SPMetadata', $state)) {
            $spmd = $state['SPMetadata'];
            $this->log('Updated SP metadata from ' . $this->spEntityId . ' to ' . $spmd['entityid']);
        }
        $spEntityId = $spmd['entityid'];

        if (!empty($spEntityId)) {
            $idpList = DiscoUtils::getReducedIdpList(
                $idpList,
                $this->getMetadataPath(),
                $spEntityId
            );
        }

        return array($spEntityId, $idpList);
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

        // Get the SP metadata entry
        $spEntries = Metadata::getSpMetadataEntries($this->getMetadataPath());
        $sp = $spEntries[$spEntityId];

        $t = new Template($this->config, 'selectidp-links', 'disco');

        $t->data['idp_list'] = $idpList;
        $t->data['return'] = $this->returnURL;
        $t->data['return_id_param'] = $this->returnIdParam;
        $t->data['entity_id'] = $this->spEntityId;
        $t->data['sp'] = $sp;
        $t->data['announcement'] = AnnouncementUtils::getAnnouncement();
        $t->data['help_center_url'] = $this->config->getOptionalString('helpCenterUrl', '');

        $t->send();
    }

    /**
     * @inheritDoc
     */
    protected function validateIdP(?string $idp): ?string
    {
        if ($idp === null) {
            return null;
        }
        if (!$this->config->getOptionalBoolean('idpdisco.validate', true)) {
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

        if (array_key_exists($idp, $idpList)) {
            return $idp;
        }
        $this->log('Invalid IdP entity id [' . $idp . '] received from discovery page.');
        // the entity id wasn't valid
        return null;
    }
}
