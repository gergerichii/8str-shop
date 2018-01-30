<?php
return [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'admin\controllers',
    'modules' => [],
    'components' => [
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];