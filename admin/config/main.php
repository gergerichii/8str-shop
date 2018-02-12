<?php

return [
    'id' => '', /* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['\common\modules\treeManager\TreeManagerBootstrap', '\common\modules\rbac\RbacPlusBootstrap'],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'rbac' => [
            'class' => 'common\modules\rbac\Module'
        ]
    ],
    'components' => [
        'urlManager' => [
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
