<?php
$config = [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'runtimePath' => "@themeRoot/runtime", //@themeRoot Генерится в бутстрап конфиге
    'components' => [
        'urlManager' => [
            'hostInfo' => 'https://8str.ln' // Надо задавать вручную для каждой копии
        ],
        'view' => [
            'theme' => [
                'basePath' => '@themeRoot', //@themeRoot Генерится в бутстрап конфиге
                'baseUrl' => '@web',
                'pathMap' => [
                    '@app/views' => '@themeViews', //@themeViews Генерится в бутстрап конфиге
                    '@app/modules' => '@themeViews/modules',
                    '@app/widgets' => '@themeViews/widgets',
                    '@app/layouts' => '@themeViews/layouts',
                ],
            ],
        ]
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];

return $config;