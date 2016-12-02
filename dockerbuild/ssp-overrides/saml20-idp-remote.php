<?php

use Sil\SspUtils\Metadata;

$mdPath = __DIR__; 

$startMetadata = Metadata::getIdpMetadataEntries($mdPath);

foreach ($startMetadata as $key => $value) {
    $metadata[$key] = $value;
}