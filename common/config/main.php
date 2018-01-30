<?php
$config = [
    'timeZone' => 'Europe/Moscow',
    'language' => 'ru-RU',
    'bootstrap' => [
        'catalog', 'files'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@commonFiles' => '@common/webFiles',
        '@commonFilesUri' => '/webFiles',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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
];

return $config;