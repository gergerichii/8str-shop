<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class IE9Asset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/app/dists';
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $css = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $js = [
        'js/html5shiv.js',
        'js/respond.min.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
        'condition' => 'lte IE9',
    ];
}
