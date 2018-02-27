<?php
return [
    'products/files' => [
        'class' => \common\modules\files\models\File::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/files',
    ],
    'products/images' => [
        'class' => \common\modules\files\models\Image::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/images',
        'thumbsOptions' => [
            'little' => [
                'path' => '@common/webFiles',
                'subdir' => 'products/images/little',
                'width' => 115,
                'height' => 115,
            ],
            'medium' => [
                'path' => '@common/webFiles',
                'subdir' => 'products/images/medium',
                'width' => 122,
                'height' => 170,
            ],
            'large' => [
                'path' => '@common/webFiles',
                'subdir' => 'products/images/large',
                'width' => 222,
                'height' => 311,
            ],
        ]
    ]
];
