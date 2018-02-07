<?php
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
                '@common/modules/cart/migrations',
                '@common/modules/order/migrations',
                '@yii/web/migrations',
//                '@yii/rbac/migrations',
//                '@yii/log/migrations',
//                '@yii/i18n/migrations',
//                '@yii/caching/migrations',
            ],
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'user' => [
            'class' => 'common\models\entities\User',
            'id' => 1,
        ]
    ],
    'params' => [],
];
