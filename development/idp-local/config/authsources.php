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
        'no_review:a' => [
            'eduPersonPrincipalName' => ['NO_REVIEW@idp'],
            'eduPersonTargetID' => ['11111111-1111-1111-1111-111111111111'],
            'sn' => ['Review'],
            'givenName' => ['No'],
            'mail' => ['no_review@example.com'],
            'employeeNumber' => ['11111'],
            'cn' => ['NO_REVIEW'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('+6 months')),
            ],
            'mfa' => [
                'prompt' => 'yes',
                'add' => 'no',
                'options' => [
                    [
                        'id' => 111,
                        'type' => 'backupcode',
                        'label' => '2SV #1',
                        'created_utc' => '2017-10-24T20:40:47Z',
                        'last_used_utc' => null,
                        'data' => [
                            'count' => 10
                        ],
                    ],
                ],
            ],
            'method' => [
                'add' => 'no',
            ],
            'profile_review' => 'no'
        ],
        'mfa_add:a' => [
            'eduPersonPrincipalName' => ['MFA_ADD@idp'],
            'eduPersonTargetID' => ['22222222-2222-2222-2222-222222222222'],
            'sn' => ['Add'],
            'givenName' => ['Mfa'],
            'mail' => ['mfa_add@example.com'],
            'employeeNumber' => ['22222'],
            'cn' => ['MFA_ADD'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('+6 months')),
            ],
            'mfa' => [
                'prompt' => 'no',
                'add' => 'yes',
                'options' => [],
            ],
            'method' => [
                'add' => 'no',
            ],
            'profile_review' => 'no'
        ],
        'method_add:a' => [
            'eduPersonPrincipalName' => ['METHOD_ADD@methodidp'],
            'eduPersonTargetID' => ['44444444-4444-4444-4444-444444444444'],
            'sn' => ['Add'],
            'givenName' => ['Method'],
            'mail' => ['method_add@example.com'],
            'employeeNumber' => ['44444'],
            'cn' => ['METHOD_ADD'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('+6 months')),
            ],
            'mfa' => [
                'prompt' => 'yes',
                'add' => 'no',
                'options' => [
                    [
                        'id' => 444,
                        'type' => 'backupcode',
                        'label' => '2SV #1',
                        'created_utc' => '2017-10-24T20:40:47Z',
                        'last_used_utc' => null,
                        'data' => [
                            'count' => 10
                        ],
                    ],
                ],
            ],
            'method' => [
                'add' => 'yes',
            ],
            'profile_review' => 'no'
        ],
        'profile_review:a' => [
            'eduPersonPrincipalName' => ['METHOD_REVIEW@methodidp'],
            'eduPersonTargetID' => ['55555555-5555-5555-5555-555555555555'],
            'sn' => ['Review'],
            'givenName' => ['Method'],
            'mail' => ['method_review@example.com'],
            'employeeNumber' => ['55555'],
            'cn' => ['METHOD_REVIEW'],
            'schacExpiryDate' => [
                gmdate('YmdHis\Z', strtotime('+6 months')),
            ],
            'mfa' => [
                'prompt' => 'yes',
                'add' => 'no',
                'options' => [
                    [
                        'id' => 555,
                        'type' => 'backupcode',
                        'label' => '2SV #1',
                        'created_utc' => '2017-10-24T20:40:47Z',
                        'last_used_utc' => null,
                        'data' => [
                            'count' => 10
                        ],
                    ],
                    [
                        'id' => 556,
                        'type' => 'manager',
                        'label' => '2SV #2',
                        'created_utc' => '2017-10-24T20:40:47Z',
                        'last_used_utc' => '2017-10-24T20:41:57Z',
                        'data' => [
                        ],
                    ],
                ],
            ],
            'method' => [
                'add' => 'no',
                'options' => [
                    [
                        'id' => '55555555555555555555555555555555',
                        'value' => 'method@example.com',
                        'verified' => true,
                        'created' => '2017-10-24T20:40:47Z',
                    ],
                ],
            ],
            'profile_review' => 'yes'
        ],
    ],
];
