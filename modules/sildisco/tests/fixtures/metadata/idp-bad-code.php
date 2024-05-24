<?php

return [
    'idp-empty' => [
        'SingleSignOnService'  => 'http://idp-empty/saml2/idp/SSOService.php',
        'IDPNamespace' => '',
    ],
    'idp-bad' => [
        'SingleSignOnService'  => 'http://idp-bad/saml2/idp/SSOService.php',
        'IDPNamespace' => 'ba!d!',
    ],
];