<?php
return [
    'components' => [
        'user' => [
            'identityCookie' => ['name' => '_identity8str', 'httpOnly' => true, 'domain' => '.8str.ru'],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
];