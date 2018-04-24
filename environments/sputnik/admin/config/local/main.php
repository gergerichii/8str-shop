<?php
return [
    'components' => [
        'urlManager' => [
            'hostInfo' => 'https://admin.sputnikvideo.ru'
        ],
        'user' => [
            'identityCookie' => ['name' => '_identitysputnikvideo', 'httpOnly' => true, 'domain' => '.sputnikvideo.ru'],
        ],
    ],
];
