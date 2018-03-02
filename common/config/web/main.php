<?php

$config = [
    'bootstrap' => [
        'log',
        'cart',
        'common\modules\order\Bootstrap',
        'files',
    ],
    'controllerMap' => [
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\entities\User',
            'enableAutoLogin' => true,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the all sites
            'name' => 'sess8str',
        ],
        'request' => [
            'csrfParam' => '_csrf-8str',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'enableStrictParsing' => true,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
            ],
            'rules' => [
                '/' => 'site/index',
                '/login' => '/site/login',
                '/logout' => '/site/logout',
                '/error' => '/site/error',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'modules' => [
        'files' => [
            'class' => 'common\modules\files\Module',
            'entities' => require 'filesMap.php'
        ],
        'cart' => [
            'class' => 'common\modules\cart\Module',
        ],
        'order' => [
            'class' => 'common\modules\order\Module',
        ],
        'rbac' => [
            'class' => 'common\modules\rbac\Module',
        ],
        'treemanager' => [
            'class' => '\common\modules\treeManager\Module',
            'treeStructure' => [
                'treeAttribute' => 'tree',
                'leftAttribute' => 'left_key',
                'rightAttribute' => 'right_key',
                'depthAttribute' => 'level',
            ]
        ],
        'news' => '\common\modules\news\Module'
    ],
];

if (YII_DEBUG && !YII_ENV_TEST) {
    $allowedIPs = ['127.0.0.1', '::1', '192.168.10.1']; // регулируйте в соответствии со своими нуждами

    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => $allowedIPs
    ];
}

return $config;