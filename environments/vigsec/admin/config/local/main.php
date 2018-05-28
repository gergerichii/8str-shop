<?php
return [
    'components' => [
        'urlManager' => [
            'hostInfo' => 'http://admin.vigsec.ru'
        ],
        'user' => [
            'identityCookie' => ['name' => '_identitysputnikvideo', 'httpOnly' => true, 'domain' => '.vigsec.ru'],
        ],
    ],
];
