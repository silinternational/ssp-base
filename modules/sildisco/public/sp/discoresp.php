<?php
/**
 * Modified version of modules/saml/public/sp/discoresp.php
 * 2024-06-06 -- Merged with simplesamlphp 1.19.8, lines marked with GTIS are modified
 */

/**
 * Handler for response from IdP discovery service.
 */

if (!array_key_exists('AuthID', $_REQUEST)) {
    throw new \SimpleSAML\Error\BadRequest('Missing AuthID to discovery service response handler');
}

if (!array_key_exists('idpentityid', $_REQUEST)) {
    throw new \SimpleSAML\Error\BadRequest('Missing idpentityid to discovery service response handler');
}

/** @var array $state */
$state = \SimpleSAML\Auth\State::loadState($_REQUEST['AuthID'], 'saml:sp:sso');

// Find authentication source
assert(array_key_exists('saml:sp:AuthId', $state));
$sourceId = $state['saml:sp:AuthId'];

$source = \SimpleSAML\Auth\Source::getById($sourceId);
if ($source === null) {
    throw new Exception('Could not find authentication source with id ' . $sourceId);
}
if (!($source instanceof \SimpleSAML\Module\sildisco\Auth\Source\SP)) { // GTIS
    throw new \SimpleSAML\Error\Exception('Source type changed?');
}

$source->startSSO($_REQUEST['idpentityid'], $state);
