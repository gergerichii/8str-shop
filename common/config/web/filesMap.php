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
            'medium' => 'products/images/medium',
            'large' => 'products/images/large',
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
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'products/images/medium' => [
        'class' => \common\modules\files\models\Thumb::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/images/medium',
        'width' => 122,
        'height' => 170,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'products/images/large' => [
        'class' => \common\modules\files\models\Thumb::class,
        'path' => '@common/webFiles',
        'subdir' => 'products/images/large',
        'width' => 222,
        'height' => 311,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'news/images' => [
        'class' => \common\modules\files\models\Image::class,
        'path' => '@common/webFiles',
        'subdir' => 'news/images',
        'thumbsOptions' => [
            'preview' => 'news/images/preview',
            'detail' => 'news/images/detail',
        ]
    ],
    'news/images/preview' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 370,
        'height' => 170,
        'path' => '@common/webFiles',
        'subdir' => 'news/images/preview',
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'news/images/detail' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 770,
        'height' => 300,
        'path' => '@common/webFiles',
        'subdir' => 'news/images/detail',
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'brands/images/' => [
        'class' => \common\modules\files\models\Image::class,
        'path' => '@common/webFiles',
        'subdir' => 'brands/images',
        'thumbsOptions' => [
            'little' => 'brands/images/little',
        ]
    ],
    'brands/images/little' => [
        'class' => \common\modules\files\models\Thumb::class,
        'path' => '@common/webFiles',
        'subdir' => 'brands/images/little',
        'width' => 170,
        'height' => 100,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
];
