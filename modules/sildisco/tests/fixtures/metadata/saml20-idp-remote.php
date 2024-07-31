<?php

$metadata['idp-empty'] = [
    'SingleSignOnService' => 'http://idp-empty/saml2/idp/SSOService.php',
    'IDPNamespace' => '',
];

$metadata['idp-bad'] = [
    'SingleSignOnService' => 'http://idp-bad/saml2/idp/SSOService.php',
    'IDPNamespace' => 'ba!d!',
];

$metadata['idp-bare'] = [
    'SingleSignOnService' => 'http://idp-bare/saml2/idp/SSOService.php',
];

$metadata['idp-good'] = [
    'SingleSignOnService' => 'http://idp-bare/saml2/idp/SSOService.php',
    'IDPNamespace' => 'idpGood',
];
