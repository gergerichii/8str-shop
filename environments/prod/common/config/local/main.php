<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=fbkru_yiishop',
            'username' => 'fbkru_yiishop',
            'password' => 'E&H2IsyY',
            'charset' => 'utf8',
        ],
        'old_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=fbkru_0_8str',
            'username' => 'fbkru_0_8str',
            'password' => 'HIzF2JkL',
            'charset' => 'utf8',
            'tablePrefix' => 'pdx',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
