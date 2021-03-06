<?php

$viewConfig = [
    'theme' => [
        'basePath' => '@app', //@themeRoot Генерится в бутстрап конфиге
        'baseUrl' => '@web',
        'pathMap' => [
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
        'cart',
        '\common\modules\news\NewsBootstrap',
    ],
    'modules' => [
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

