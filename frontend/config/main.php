<?php
return [
    'id' => '',/* Если не указан, то генерится КонфигМенеджером из имени папки приложения */
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
    ],
    'params' => [], /* Автоматически цепляется КонфигМенеджером */
];

