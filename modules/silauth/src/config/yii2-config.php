<?php

use Sil\JsonLog\target\JsonStreamTarget;
use yii\helpers\Json;

return [
    'basePath' => __DIR__ . '/../',
    'id' => 'SilAuth',
    'aliases' => [
        '@Sil/SilAuth' => __DIR__ . '/..',
    ],
    'bootstrap' => [
        'gii',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => null,
            'username' => null,
            'password' => null,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => JsonStreamTarget::class,
                    'url' => 'php://stdout',
                    'levels' => ['info'],
                    'logVars' => [],
                    'categories' => ['application'],
                    'prefix' => function ($message) {
                        $prefixData = [
                            'message' => $message,
                            'env' => YII_ENV,
                        ];
                        return Json::encode($prefixData);
                    },
                    'exportInterval' => 1,
                ],
                [
                    'class' => JsonStreamTarget::class,
                    'url' => 'php://stderr',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                    'prefix' => function ($message) {
                        $prefixData = [
                            'message' => $message,
                            'env' => YII_ENV,
                        ];
                        return Json::encode($prefixData);
                    },
                    'exportInterval' => 1,
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'Sil\\SilAuth\\migrations\\',
            ],
            
            // Disable non-namespaced migrations.
            'migrationPath' => null,
        ],
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ],
];
