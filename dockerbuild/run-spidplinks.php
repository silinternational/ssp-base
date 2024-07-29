<?php

include "/data/vendor/autoload.php";

use SimpleSAML\Metadata\MetaDataStorageHandler;
use SimpleSAML\Module\sildisco\IdPDisco;

echo listAllSpIdpLinks();

/**
 * Returns a nested array of all the IdP's that are available to each SP
 *
 * @return array ["sp1" => ["idp1", ...], ...]]
 */
function getSpIdpLinks(): array
{
    $links = [];
    $metadata = MetaDataStorageHandler::getMetadataHandler();
    $spEntries = $metadata->getList('saml20-sp-remote');

    foreach ($spEntries as $spEntityId => $spEntry) {
        $idpList = IdPDisco::getIdpsForSp($spEntityId);
        $links[$spEntityId] = array_keys($idpList);
    }
    return $links;
}

/**
 * Returns a nested array of all the SPs that are allowed to use each IdP
 * and all the IdPs that are available to each SP
 */
function listAllSpIdpLinks(): string
{
    $spLinks = getSpIdpLinks();
    $idpLinks = [];

    // transpose the SP-based array for the IdP-based array
    foreach ($spLinks as $nextSp => $idps) {
        foreach ($idps as $nextIdp) {
            if (!isset($idpLinks[$nextIdp])) {
                $idpLinks[$nextIdp] = [];
            }
            $idpLinks[$nextIdp][] = $nextSp;
        }
    }

    $output = PHP_EOL . "These IdPs are available to the corresponding SPs" . PHP_EOL;

    foreach ($idpLinks as $idpEntityId => $spList) {
        $output .= '  ' . $idpEntityId . ' is available to ...' . PHP_EOL;
        foreach ($spList as $nextSp) {
            $output .= '    ' . $nextSp . PHP_EOL;
        }
        $output .= '-----------' . PHP_EOL;
        $output .= PHP_EOL;
    }

    $output .= PHP_EOL . "These SPs may use the corresponding IdPs" . PHP_EOL;

    foreach ($spLinks as $spEntityId => $idpList) {
        $output .= '  ' . $spEntityId . ' may use ... ' . PHP_EOL;
        foreach ($idpList as $nextIdp) {
            $output .= '    ' . $nextIdp . PHP_EOL;
        }
        $output .= '-----------' . PHP_EOL;
        $output .= PHP_EOL;
    }

    return $output;
}
