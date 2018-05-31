<?php

$config = [
    'id' => 'admin',
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'bootstrap' => [
        'common\config\setUp',
        'common\modules\files\FilesBootstrap',
        'common\modules\cart\CartBootstrap',
        'common\modules\order\OrderBootstrap',
        'common\modules\catalog\CatalogBootstrap',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@commonFiles' => '@common/webFiles',
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
            'class' => common\components\Formatter::class,
            'currencyShowDecimals' => false,
            'currencySymbol' => '₽',
        ],
        'image' => [
            'class' => '\yii\image\ImageDriver',
            'driver' => 'GD',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'db' => 'db',  // ID компонента для взаимодействия с БД. По умолчанию 'db'.
            'sessionTable' => 'session', // название таблицы для хранения данных сессии. По умолчанию 'session'.
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => defined('YII_DEBUG') && YII_DEBUG,
            'view' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.beget.ru',
                'username' => 'support@8str.ru',
                'password' => 'Volga2015',
                'port' => '2525',
                'encryption' => 'tls',
            ],
        ],
    ],
    'modules' => [
        'catalog' => [
            'class' => 'common\modules\catalog\CatalogModule',
        ],
        'cart' => [
            'class' => 'common\modules\cart\CartModule',
        ],
        'order' => [
            'class' => 'common\modules\order\Module',
        ],
        'files' => [
            'class' => 'common\modules\files\FilesModule',
            'entities' => require 'filesMap.php',
            'publicPath' => '@common/webFiles',
            'protectedPath' => '@common/webFilesProtected',
        ],
    ],
    'container' => [
        'definitions' => [
            'yii\i18n\Formatter' => 'common\components\Formatter'
        ],
    ],
];

return $config;