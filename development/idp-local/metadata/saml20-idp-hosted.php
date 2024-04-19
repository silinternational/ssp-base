<?php

use Sil\PhpEnv\Env;
use Sil\Psr3Adapters\Psr3SamlLogger;

/**
 * SAML 2.0 IdP configuration for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-idp-hosted
 */

use Sil\Psr3Adapters\Psr3StdOutLogger;

$metadata['http://ssp-idp1.local:8085'] = [
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

    'authproc' => [
        15 => [
            'class' => 'expirychecker:ExpiryDate',
            'accountNameAttr' => 'cn',
            'expiryDateAttr' => 'schacExpiryDate',
            'passwordChangeUrl' => 'http://www.example.com/change-password',
            'warnDaysBefore' => 14,
            'dateFormat' => 'Y-m-d',
            'loggerClass' => Psr3StdOutLogger::class,
        ],
        30 => [
            'class' => 'profilereview:ProfileReview',
            'employeeIdAttr' => 'employeeNumber',
            'mfaLearnMoreUrl' => Env::get('MFA_LEARN_MORE_URL'),
            'profileUrl' => Env::get('PROFILE_URL'),
            'loggerClass' => Psr3SamlLogger::class,
        ],
    ],
];

// Copy configuration for port 80 and modify host and profileUrl.
$metadata['http://ssp-idp1.local'] = $metadata['http://ssp-idp1.local:8085'];
$metadata['http://ssp-idp1.local']['host'] = 'ssp-idp1.local';
$metadata['http://ssp-idp1.local']['authproc'][30]['profileUrl'] = Env::get('PROFILE_URL_FOR_TESTS');
