<?php
return [
    // TODO Need to configure access to files
    'products/files' => [
        'class' => \common\modules\files\models\File::class,
    ],
    'products/images' => [
        'class' => \common\modules\files\models\Image::class,
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
        'resizingConstraint' => \yii\image\drivers\Image::CROP
    ],
    'products/images/medium' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 122,
        'height' => 170,
        'resizingConstraint' => \yii\image\drivers\Image::CROP
    ],
    'products/images/large' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 222,
        'height' => 311,
        'resizingConstraint' => \yii\image\drivers\Image::CROP
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
        'resizingConstraint' => \yii\image\drivers\Image::CROP
    ],
    'news/images/detail' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 770,
        'height' => 300,
        'resizingConstraint' => \yii\image\drivers\Image::CROP
    ],
    'brands/images/' => [
        'class' => \common\modules\files\models\Image::class,
        'thumbsOptions' => [
            'little' => 'brands/images/little',
        ]
    ],
    'brands/images/little' => [
        'class' => \common\modules\files\models\Thumb::class,
        'width' => 170,
        'height' => 100,
        'resizingConstraint' => \yii\image\drivers\Image::CROP
    ],
];
