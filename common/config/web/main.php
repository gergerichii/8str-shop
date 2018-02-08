<?php

$config = [
    'bootstrap' => [
        'log',
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
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'collapseSlashes' => true,
                'normalizeTrailingSlash' => true,
            ],
            'rules' => [
            ],
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
        ],
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