<?php

$config = [

    // This is a authentication source which handles admin authentication.
    'admin' => [
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ],
    
    // Set up example users for testing expirychecker module.
    'example-userpass' => [
        'exampleauth:UserPass',
        'distant_future:a' => [
            'eduPersonPrincipalName' => ['DISTANT_FUTURE@ssp-idp1.local'],
            'sn' => ['Future'],
            'givenName' => ['Distant'],
            'mail' => ['distant_future@example.com'],
            'employeeNumber' => ['11111'],
            'cn' => ['DISTANT_FUTURE'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('+6 months')), // Distant future
            ],
        ],
        'near_future:b' => [
            'eduPersonPrincipalName' => ['NEAR_FUTURE@ssp-idp1.local'],
            'sn' => ['Future'],
            'givenName' => ['Near'],
            'mail' => ['near_future@example.com'],
            'employeeNumber' => ['22222'],
            'cn' => ['NEAR_FUTURE'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('+1 day')), // Very soon
            ],
        ],
        'already_past:c' => [
            'eduPersonPrincipalName' => ['ALREADY_PAST@ssp-idp1.local'],
            'sn' => ['Past'],
            'givenName' => ['Already'],
            'mail' => ['already_past@example.com'],
            'employeeNumber' => ['33333'],
            'cn' => ['ALREADY_PAST'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('-1 day')), // In the past
            ],
        ],
        'missing_exp:d' => [
            'eduPersonPrincipalName' => ['MISSING_EXP@ssp-idp-1.local'],
            'sn' => ['Expiration'],
            'givenName' => ['Missing'],
            'mail' => ['missing_exp@example.com'],
            'employeeNumber' => ['44444'],
            'cn' => ['MISSING_EXP'],
        ],
        'invalid_exp:e' => [
            'eduPersonPrincipalName' => ['INVALID_EXP@ssp-idp-1.local'],
            'sn' => ['Expiration'],
            'givenName' => ['Invalid'],
            'mail' => ['invalid_exp@example.com'],
            'employeeNumber' => ['55555'],
            'cn' => ['INVALID_EXP'],
            'schacExpiryDate' => [
                'invalid'
            ],
        ],
    ],
];
