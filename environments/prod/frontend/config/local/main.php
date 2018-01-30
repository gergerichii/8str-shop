<?php
return [
    'components' => [
        'urlManager' => [
            'hostInfo' => 'test.8str.ru'
        ],
        'user' => [
            'identityCookie' => ['name' => '_identity8str', 'httpOnly' => true, 'domain' => '.8str.ru'],
        ],
    ]
];
