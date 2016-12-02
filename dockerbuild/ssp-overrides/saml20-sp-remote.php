<?php

use Sil\SspUtils\Metadata;

$mdPath = __DIR__; 

$startMetadata = Metadata::getSpMetadataEntries($mdPath);

foreach ($startMetadata as $key => $value) {
    $metadata[$key] = $value;
}