<?php

/**
 * Custom IdP discovery service.
 */

$discoHandler = new \SimpleSAML\Module\sildisco\IdPDisco(['saml20-idp-remote'], 'saml');

$discoHandler->handleRequest();
