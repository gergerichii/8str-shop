<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@frontend/assets/app/dists';
//    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $css = [
//        'css/main.css',
        'css/style.css',
        'css/responsive.css',
        'css/animate.css',
        'css/bootstrap-switch.css',
        'css/jquery.selectbox.css',
        'css/owl.carousel.css',
        'css/prettyPhoto.css',
        'css/revslider.css',
        'css/colpick.css',
        'css/jquery.typeahead.css'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
//        'frontend\assets\JQuery11Asset',
//        'common\assets\CommonAsset',
        'rmrevin\yii\fontawesome\cdn\AssetBundle',
        'frontend\assets\IE9Asset',
    ];
    public $js = [
        'js/smoothscroll.js',
        'js/bootstrap-switch.min.js',
        'js/retina.min.js',
        'js/jquery.placeholder.js',
        'js/jquery.hoverIntent.min.js',
        'js/twitter/jquery.tweet.min.js',
        'js/jquery.flexslider-min.js',
        'js/owl.carousel.min.js',
        'js/jflickrfeed.min.js',
        'js/jquery.prettyPhoto.js',
        'js/jquery.themepunch.tools.min.js',
        'js/jquery.themepunch.revolution.js',
        'js/jquery.elevateZoom.min.js',
        'js/jquery.fitvids.js',
        'js/jquery.elastislide.js',
        'js/jquery.selectbox.min.js',
        'js/jquery.debouncedresize.js',
        'js/jquery.typeahead.js',
        'js/colpick.js',
        'js/main.js',
    ];

    //    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
