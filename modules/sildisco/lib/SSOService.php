<?php
/**
 *
 * Note: This has been copied from the core code (www/saml2/idp/SSOService.php version 1.19.6)
 *   and modified to call a different authentication class/method
 *
 * Original comments ...
 *
 * The SSOService is part of the SAML 2.0 IdP code, and it receives incoming Authentication Requests
 * from a SAML 2.0 SP, parses, and process it, and then authenticates the user and sends the user back
 * to the SP with an Authentication Response.
 *
 * @author Andreas Åkre Solberg, UNINETT AS. <andreas.solberg@uninett.no>
 * @package SimpleSAMLphp
 */

require_once('../../_include.php');

\SimpleSAML\Logger::info('SAML2.0 - IdP.SSOService: Accessing SAML 2.0 IdP endpoint SSOService');

$metadata = \SimpleSAML\Metadata\MetaDataStorageHandler::getMetadataHandler();

$config = \SimpleSAML\Configuration::getInstance();
if (!$config->getBoolean('enable.saml20-idp', false) || !\SimpleSAML\Module::isModuleEnabled('saml')) {
    throw new \SimpleSAML\Error\Error('NOACCESS', null, 403);
}

$idpEntityId = $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
$idp = \SimpleSAML\IdP::getById('saml2:' . $idpEntityId);

$hubModeKey = 'hubmode';

try {
// If in hub mode, then use the sildisco entry script
    if ($config->getValue($hubModeKey, false)) {
        \SimpleSAML\Module\sildisco\IdP\SAML2::receiveAuthnRequest($idp);
    } else {
        \SimpleSAML\Module\saml\IdP\SAML2::receiveAuthnRequest($idp);
    }
} catch (\Exception $e) {
    if ($e->getMessage() === "Unable to find the current binding.") {
        throw new \SimpleSAML\Error\Error('SSOPARAMS', $e, 400);
    } else {
        throw $e; // do not ignore other exceptions!
    }
}
assert(false);
