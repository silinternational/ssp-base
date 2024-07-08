<?php

/**
 * Custom IdP discovery service. Built-in service is in modules/saml/public/disco.php
 */

$discoHandler = new \SimpleSAML\Module\sildisco\IdPDisco(['saml20-idp-remote'], 'saml');

$discoHandler->handleRequest();
