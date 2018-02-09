<?php

$config = [
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'bootstrap' => [
        'common\config\setUp', 'catalog', 'files'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@commonFiles' => '@common/webFiles',
        '@commonFilesUri' => '/webFiles',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'i18n' => [
            'translations' => [
                'app.common' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ]
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => common\components\Formatter::className(),
            'currencyShowDecimals' => false,
            'currencySymbol' => '₽',
        ],
    ],
    'modules' => [
        'catalog' => [
            'class' => 'common\modules\catalog\Module',
        ],
        'files' => [
            'class' => 'common\modules\files\Module',
        ],
    ],
    'container' => [
        'definitions' => [
            'yii\i18n\Formatter' => 'common\components\Formatter'
        ],
    ],
];

return $config;