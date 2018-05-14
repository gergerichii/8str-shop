<?php
$config = [
    'components' => [
        'user' => [
            'identityCookie' => ['name' => '_identity8str', 'httpOnly' => true, 'domain' => '.vigsec.ru'],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];

$config['modules']['debug']['allowedIPs'][] = '195.19.215.184';
$config['modules']['debug']['allowedIPs'][] = '10.93.129.178';

return $config;