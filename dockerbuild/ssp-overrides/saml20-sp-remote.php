<?php

use Sil\SspUtils\Metadata;

$files = Metadata::getMetadataFiles(__DIR__, 'sp');

foreach ($files as $file) {
    include $file;
}
