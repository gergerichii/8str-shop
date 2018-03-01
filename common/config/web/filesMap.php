<?php
return [
    // TODO Need to configure access to files
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
            'little' => 'products/images/little',
            'medium' => 'products/images/little',
            'large' => 'products/images/little',
        ]
    ],
    'defaults' => [
        'class' => \common\modules\files\models\Image::class,
        'path' => '@common/webFiles',
        'subdir' => 'defaults',
    ],
    'products/images/little' => [
        'class' => \common\modules\files\models\Thumb::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/images/little',
        'width' => 115,
        'height' => 115,
    ],
    'products/images/medium' => [
        'class' => \common\modules\files\models\Thumb::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/images/medium',
        'width' => 122,
        'height' => 170,
    ],
    'products/images/large' => [
        'class' => \common\modules\files\models\Thumb::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/images/large',
        'width' => 222,
        'height' => 311,
    ],
];
