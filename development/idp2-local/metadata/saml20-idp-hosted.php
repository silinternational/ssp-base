<?php
/**
 * SAML 2.0 IdP configuration for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
 */

$metadata['http://ssp-idp2.local:8086'] = [
    'entityid' => 'http://ssp-idp2.local:8086',
    'name' => ['en' => 'IDP 2'],

    /*
     * The hostname of the server (VHOST) that will use this SAML entity.
     *
     * Can be '__DEFAULT__', to use this entry by default.
     */
    'host' => '__DEFAULT__',

    // X.509 key and certificate. Relative to the cert directory.
    'privatekey' => 'ssp-hub-idp2.pem',
    'certificate' => 'ssp-hub-idp2.crt',

    /*
     * Authentication source to use. Must be one that is configured in
     * 'config/authsources.php'.
     */
    'auth' => 'silauth',
];

// Copy configuration for port 80 and modify host.
$metadata['http://ssp-idp2.local'] = $metadata['http://ssp-idp2.local:8086'];
$metadata['http://ssp-idp2.local']['entityid'] = 'http://ssp-idp2.local';
$metadata['http://ssp-idp2.local']['host'] = 'ssp-idp2.local';
