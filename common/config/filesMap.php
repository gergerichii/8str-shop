<?php
return [
    // TODO Need to configure access to files
    'products/files' => [
        'class' => \common\modules\files\models\File::class,
    ],
    'products/images' => [
        'class' => \common\modules\files\models\Image::class,
        'oldImagesDir' => 'old',
        'thumbsOptions' => [
            'little' => 'products/images/little',
            'medium' => 'products/images/medium',
            'large' => 'products/images/large',
        ],
        'width' => 800,
        'height' => 1120,
    ],
    'defaults' => [
        'class' => \common\modules\files\models\Image::class,
        'fileName' => 'pixel.png',
    ],
    'products/images/little' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 115,
        'height' => 115,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'products/images/medium' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 122,
        'height' => 170,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'products/images/large' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 222,
        'height' => 311,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'news/images' => [
        'class' => \common\modules\files\models\Image::class,
        'thumbsOptions' => [
            'preview' => 'news/images/preview',
            'detail' => 'news/images/detail',
        ]
    ],
    'news/images/preview' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 370,
        'height' => 170,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ],
    'news/images/detail' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 770,
        'height' => 300,
        'resizingConstrait' => \yii\image\drivers\Image::CROP
    ]
];
