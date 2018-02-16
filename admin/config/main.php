<?php

return [
    'id' => '', /* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => [
        '\common\modules\rbac\RbacPlusBootstrap',
        '\common\modules\order\Bootstrap',
        '\common\modules\treeManager\TreeManagerBootstrap',
        '\common\modules\catalog\CatalogAdminBootstrap'
    ],
    'modules' => [
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
                // kartik\grid for export in rbac
                '/gridview/export/download' => '/gridview/export/download',
            ],
        ],
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];
