<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

define('YII_THEME', '_8str');
Yii::setAlias('@themeRoot', dirname(dirname(__FILE__)) . '/themes/' . YII_THEME);
Yii::setAlias('@themeViews', dirname(dirname(__FILE__)) . '/themes/views/' . YII_THEME);

return [
    'id' => 'app-frontend',
    'name' => 'Мой магазин',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => function () {
            return Yii::$app->get('frontendUrlManager');
        },
        'view' => [
            'theme' => [
                'basePath' => '@themeRoot',
                'baseUrl' => '@web',
                'pathMap' => [
                    '@app/views' => [
                        '@themeRoot/views'
                    ],
                    '@app/modules' => [
                        '@themeRoot/modules'
                    ],
                    '@app/widgets' => [
                        '@themeRoot/widgets'
                    ],
                ],
            ],
        ],
        'cart' => [
            'class' => 'dvizh\cart\Cart',
            'currency' => 'р.', //Валюта
            'currencyPosition' => 'after', //after или before (позиция значка валюты относительно цены)
            'priceFormat' => [0,'.', ''], //Форма цены
        ],
        'assetManager' => [
        ],
    ],
    'modules' => [
        'cart' => [
            'class' => 'dvizh\cart\Module',
        ],
    ],
    'params' => $params,
];
