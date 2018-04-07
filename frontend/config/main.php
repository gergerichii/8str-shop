<?php

$viewConfig = [
    'theme' => [
        'basePath' => '@app', //@themeRoot Генерится в бутстрап конфиге
        'baseUrl' => '@web',
        'pathMap' => [
            '@common/modules' => '@app/views/modules',
            '@common/widgets' => '@app/views/widgets',
            '@common/mail' => '@app/mail',
        ],
    ],
];

return [
    'name' => 'Интернет магазин систем видеонаблюдения и безопасности "Восьмой страж"',
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'bootstrap' => [
        'common\modules\order\Bootstrap',
        'catalog',
        'cart',
        '\common\modules\news\NewsBootstrap',
    ],
    'modules' => [
        'catalog' => [
            'class' => 'common\modules\catalog\Module',
        ],
        'order' => [
            'class' => common\modules\order\Module::class,
        ],
        'cart' => [
            'class' => common\modules\cart\Module::class,
        ],
    ],
    'components' => [
        'view' => $viewConfig,
        'urlManager' => [
            'rules' => [
                '/data/<_a:(search)>.json' => '/data/<_a>',
                '/contacts' => '/site/contacts',
                '/delivery-and-payments' => '/site/delivery-and-payments',
            ]
        ],
        
        'mailer' => [
            'view' => $viewConfig,
        ],
        
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];

