<?php

use Sil\SspUtils\Metadata;

$files = Metadata::getMetadataFiles(__DIR__, 'idp');

foreach ($files as $file) {
    include $file;
}
