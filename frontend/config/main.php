<?php
return [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
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

