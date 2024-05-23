<?php

use SimpleSAML\Module\silauth\Auth\Source\config\ConfigManager;

$config = [

    // This is a authentication source which handles admin authentication.
    'admin' => [
        // The default is to use core:AdminPassword, but it can be replaced with
        // any authentication source.

        'core:AdminPassword',
    ],

    // Use SilAuth
    'silauth' => ConfigManager::getSspConfig(),
];
