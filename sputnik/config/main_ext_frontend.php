<?php
$config = [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'name' => 'Интернет магазин систем видеонаблюдения и безопасности "Восьмой страж"',
    'runtimePath' => "@themeRoot/runtime", //@themeRoot Генерится в бутстрап конфиге
    'components' => [
        'view' => [
            'theme' => [
                'basePath' => '@themeRoot', //@themeRoot Генерится в бутстрап конфиге
                'baseUrl' => '@web',
                'pathMap' => [
                    '@app/views' => '@themeViews', //@themeViews Генерится в бутстрап конфиге
                    '@app/modules' => '@themeViews/modules',
                    '@app/widgets' => '@themeViews/widgets',
                    '@app/layouts' => '@themeViews/layouts',
                    /* Так как приложение зависимое, то сначала надо просмотреть то что лежить в родительском приложении */
                    '@common/modules' => ['@themeViews/modules', '@app/views/modules'],
                    '@common/widgets' => ['@themeViews/widgets', '@app/views/widgets'],
                ],
            ],
        ]
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];

return $config;