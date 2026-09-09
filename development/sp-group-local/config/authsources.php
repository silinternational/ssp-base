<?php

$config = [

    // This is a authentication source which handles admin authentication.
    'admin' => [
        'core:AdminPassword',
    ],

    'mfa-idp' => [
        'saml:SP',
        'entityID' => 'https://pwmanager.local',
        'idp' => 'https://ssp-idp1.local',
        'discoURL' => null,
        'NameIDPolicy' => [
            'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'AllowCreate' => true,
        ],
    ],

    'sp1' => [
        'saml:SP',
        'entityID' => 'https://ssp-sp1.local',
        'idp' => 'ssp-hub.local',
        'discoURL' => null,
        'NameIDPolicy' => [
            'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'AllowCreate' => true,
        ],
        'privatekey' => 'saml-sp.pem',
    ],

    'sp2' => [
        'saml:SP',
        'entityID' => 'https://ssp-sp2.local',
        'idp' => 'ssp-hub.local',
        'discoURL' => null,
        'privatekey' => 'saml-sp.pem',
    ],

    'sp3' => [
        'saml:SP',
        'entityID' => 'https://ssp-sp3.local',
        'idp' => 'ssp-hub.local',
        'discoURL' => null,
        'privatekey' => 'saml-sp.pem',
    ],

    'sp4' => [
        'saml:SP',
        'entityID' => 'https://ssp-sp4.local',
        'idp' => 'ssp-hub.local',
        'discoURL' => null,
        'NameIDPolicy' => [
            'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'AllowCreate' => true,
        ],
        'privatekey' => 'saml-sp.pem',
    ],


    'sp5' => [
        'saml:SP',
        'entityID' => 'https://ssp-sp5.local',
        'idp' => 'ssp-hub.local',
        'discoURL' => null,
        'NameIDPolicy' => [
            'Format' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'AllowCreate' => true,
        ],
        'privatekey' => 'saml-sp.pem',
    ],

];
