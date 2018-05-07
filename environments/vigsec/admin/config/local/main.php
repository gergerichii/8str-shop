<?php
return [
    'components' => [
        'urlManager' => [
            'hostInfo' => 'https://admin.vigsec.ru'
        ],
        'user' => [
            'identityCookie' => ['name' => '_identitysputnikvideo', 'httpOnly' => true, 'domain' => '.vigsec.ru'],
        ],
    ],
];
