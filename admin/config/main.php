<?php
return [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'bootstrap' => ['\common\modules\treeManager\TreeManagerBootstrap'],
    'modules' => [
        'treemanager' => [
            'class' => '\common\modules\treeManager\Module',
        ]
    ],
    'components' => [
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];