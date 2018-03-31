<?php
$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=tima_shop',
            'username' => 'homestead',
            'password' => 'secret',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'transport' => [
                'port' => '25',
            ],
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=54729;',
            'username' => 'homestead',
            'password' => 'secret',
        ],
    ],
];

if (YII_ENV_DEV && !YII_ENV_TEST) {
    $allowedIPs = ['127.0.0.1', '::1', '192.168.10.1'];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $allowedIPs,
    ];
}

return $config;