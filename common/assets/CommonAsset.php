<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 24.12.2017
 * Time: 17:54
 */

namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle {
    public $sourcePath = '@common/assets/common/dists';
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $css = [
        'css/my_bootstrap.css',
    ];

    public $js = [
        'js/popper.js',
        'js/bootstrap.js',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}