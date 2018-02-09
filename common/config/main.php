<?php

$config = [
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'bootstrap' => [
        'common\config\setUp'
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
                    'class' => 'common\i18n\PhpMessageSource',
                    'basePath' => [
                        '@common/messages',
                        '@app/messages',
                    ],
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
    ],
    'container' => [
        'definitions' => [
            'yii\i18n\Formatter' => 'common\components\Formatter'
        ],
    ],
];

return $config;