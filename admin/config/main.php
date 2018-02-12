<?php

return [
    'id' => '', /* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['\common\modules\treeManager\TreeManagerBootstrap', '\common\modules\rbac\RbacPlusBootstrap',
                    'common\modules\order\Bootstrap',
            'catalog',
            \common\modules\rbac\RbacPlusBootstrap::className(),
        
        ],
    'modules' => [
        'treemanager' => [
            'class' => '\common\modules\treeManager\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
        'rbac' => [
            'class' => 'common\modules\rbac\Module'
        ],
        'order' => [
            'class' => common\modules\order\Module::className(),
            'controllerNamespace' => 'common\modules\order\controllers\admin',
            'viewPath' => '@common/modules/order/views/admin'
        ],
        'catalog' => [
            'class' => 'common\modules\catalog\Module',
            'controllerNamespace' => 'common\modules\catalog\controllers\admin',
            'viewPath' => '@common/modules/catalog/views/admin'
        ],
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
