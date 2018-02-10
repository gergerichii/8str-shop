<?php

return [
    'id' => '', /* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => [\common\modules\rbac\RbacPlusBootstrap::class],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => '/site/index',
                '/login' => '/site/login',
                '/logout' => '/site/logout',
                '/error' => '/site/error',
                // kartik\grid for export in rbac
                '/gridview/export/download' => '/gridview/export/download',
            ],
        ],
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];
