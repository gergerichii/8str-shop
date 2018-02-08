<?php
return [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => [
        'common\modules\order\Bootstrap',
        'catalog',
        'cart',
    ],
    'modules' => [
        'catalog' => [
            'class' => 'common\modules\catalog\Module',
        ],
        'order' => [
            'class' => common\modules\order\Module::className(),
        ],
        'cart' => [
            'class' => common\modules\cart\Module::className(),
        ],
    ],
    'components' => [
        'view' => [
            'theme' => [
                'basePath' => '@app', //@themeRoot Генерится в бутстрап конфиге
                'baseUrl' => '@web',
                'pathMap' => [
                    '@common/modules' => '@app/views/modules',
                    '@common/widgets' => '@app/views/widgets',
                ],
            ],
        ]
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];

