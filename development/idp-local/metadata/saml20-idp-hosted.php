<?php
/**
 * SAML 2.0 IdP configuration for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
 */

$metadata['http://ssp-hub-idp.local:8085'] = [
	/*
	 * The hostname of the server (VHOST) that will use this SAML entity.
	 *
	 * Can be '__DEFAULT__', to use this entry by default.
	 */
	'host' => '__DEFAULT__',

	// X.509 key and certificate. Relative to the cert directory.
	'privatekey' => 'ssp-hub-idp.pem',
	'certificate' => 'ssp-hub-idp.crt',
    
    'logoURL' => 'https://dummyimage.com/125x125/0f4fbd/ffffff.png&text=IDP+1+8085',

	/*
	 * Authentication source to use. Must be one that is configured in
	 * 'config/authsources.php'.
	 */
    'auth' => 'example-userpass',
];

// Duplicate configuration for port 80.
$metadata['http://ssp-hub-idp.local'] = $metadata['http://ssp-hub-idp.local:8085'];
