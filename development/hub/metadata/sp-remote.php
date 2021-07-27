<?php
/**
 * SAML 2.0 remote SP metadata for SimpleSAMLphp.
 *
 * See: https://simplesamlphp.org/docs/stable/simplesamlphp-reference-sp-remote
 */

return [
    /*
     * Example SimpleSAMLphp SAML 2.0 SP
     */
    'http://ssp-hub-sp.local:8081' => [
        'IDPList' => [
            'http://ssp-hub-idp.local:8085',
            'http://ssp-hub-idp2.local:8086',
        ],
        'name' => "SP Local",
        'AssertionConsumerService' => 'http://ssp-hub-sp.local:8081/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp.local:8081/module.php/saml/sp/saml2-logout.php/ssp-hub',
    ],
    'http://ssp-hub-sp.local' => [
        'IDPList' => [
            'http://ssp-hub-idp.local',
            'http://ssp-hub-idp2.local',
        ],
        'name' => "SP Local",
        'AssertionConsumerService' => 'http://ssp-hub-sp.local/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp.local/module.php/saml/sp/saml2-logout.php/ssp-hub',
    ],

    'http://ssp-hub-sp2.local:8082' => [
        'AssertionConsumerService' => 'http://ssp-hub-sp2.local:8082/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp2.local:8082/module.php/saml/sp/saml2-logout.php/ssp-hub',
        'IDPList' => [
            'http://ssp-hub-idp2.local:8086',
        ],
    ],
    'http://ssp-hub-sp2.local' => [
        'AssertionConsumerService' => 'http://ssp-hub-sp2.local/module.php/saml/sp/saml2-acs.php/ssp-hub',
        'SingleLogoutService' => 'http://ssp-hub-sp2.local/module.php/saml/sp/saml2-logout.php/ssp-hub',
        'IDPList' => [
            'http://ssp-hub-idp2.local',
        ],
    ],
];
