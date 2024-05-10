<?php

$config = [

    // This is a authentication source which handles admin authentication.
    'admin' => [
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ],

    'mfa-idp' => [
        'saml:SP',
        'entityID' => 'http://pwmanager.local:8083',
        'idp' => 'http://ssp-idp1.local:8085',
        'discoURL' => null,
        'NameIDPolicy' => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],

    'mfa-idp-no-port' => [
        'saml:SP',
        'entityID' => 'http://pwmanager.local',
        'idp' => 'http://ssp-idp1.local',
        'discoURL' => null,
        'NameIDPolicy' => "urn:oasis:names:tc:SAML:2.0:nameid-format:persistent",
    ],
];
