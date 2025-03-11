<?php

$config = [

    // This is a authentication source which handles admin authentication.
    'admin' => [
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ],

    /**
     * Example users for testing. Users here MUST also be in IDP 1's list,
     * because we test logging into each with the same credentials in order to
     * test the MFA recovery contacts.
     */
    'example-userpass' => [
        'exampleauth:UserPass',

        'users' => [

            // mfa test user who requires mfa and has backup codes
            'has_backupcode:a' => [
                'eduPersonPrincipalName' => ['HAS_BACKUPCODE@mfaidp'],
                'eduPersonTargetID' => ['33333333-3333-3333-3333-333333333333'],
                'sn' => ['Backupcode'],
                'givenName' => ['Has'],
                'mail' => ['has_backupcode@example.com'],
                'employeeNumber' => ['33333'],
                'cn' => ['HAS_BACKUPCODE'],
                'schacExpiryDate' => [
                    gmdate('YmdHis\Z', strtotime('+6 months')),
                ],
                'profile_review' => 'no',
                'mfa' => [
                    'prompt' => 'yes',
                    'add' => 'no',
                    'options' => [
                        [
                            'id' => '7',
                            'type' => 'backupcode',
                            'data' => [
                                'count' => 10,
                            ],
                        ],
                    ],
                ],
                'method' => [
                    'add' => 'no',
                    'options' => [],
                ],
            ],

            // mfa test user who requires mfa and has backup codes and a manager email
            'has_backupcode_and_mgr:a' => [
                'eduPersonPrincipalName' => ['HAS_BACKUPCODE@mfaidp'],
                'eduPersonTargetID' => ['33333333-3333-3333-3333-333333333333'],
                'sn' => ['Backupcode'],
                'givenName' => ['Has'],
                'mail' => ['has_backupcode@example.com'],
                'employeeNumber' => ['33333'],
                'cn' => ['HAS_BACKUPCODE'],
                'schacExpiryDate' => [
                    gmdate('YmdHis\Z', strtotime('+6 months')),
                ],
                'profile_review' => 'no',
                'mfa' => [
                    'prompt' => 'yes',
                    'add' => 'no',
                    'options' => [
                        [
                            'id' => '7',
                            'type' => 'backupcode',
                            'data' => [
                                'count' => 10,
                            ],
                        ],
                    ],
                ],
                'method' => [
                    'add' => 'no',
                    'options' => [],
                ],
                'manager_email' => ['manager@example.com'],
            ],
        ],
    ]
];
