<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11.01.2018
 * Time: 10:48
 */

namespace frontend\assets;
use yii\web\AssetBundle;

class JQuery11Asset extends AssetBundle {
    public $sourcePath = '@frontend/assets/app/dists';
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $js = [
        'js/jquery-1.11.1.min.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
