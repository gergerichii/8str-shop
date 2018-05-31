<?php

return [
    'id' => '', /* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => [
        '\common\modules\rbac\RbacPlusBootstrap',
        '\common\modules\order\OrderBootstrap',
        '\common\modules\cart\CartBootstrap',
        '\common\modules\files\FilesBootstrap',
        '\common\modules\catalog\CatalogBootstrap',
        '\common\modules\treeManager\TreeManagerBootstrap',
    ],
    'modules' => [
        'order' => [
            'class' => common\modules\order\Module::class,
            'controllerNamespace' => 'common\modules\order\controllers\admin',
            'viewPath' => '@common/modules/order/views/admin'
        ],
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];
