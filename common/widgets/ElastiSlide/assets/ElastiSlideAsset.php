<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 28.05.2018
 * Time: 15:57
 */

namespace common\widgets\ElastiSlide\assets;
use yii\web\AssetBundle;
use yii\web\View;

class ElastiSlideAsset extends AssetBundle {
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
    public $js = [
        'js/modernizr.custom.17475.js',
        'js/jquerypp.custom.js',
        'js/jquery.elastislide.js',
    ];
    
//    public $jsOptions = [
//        'position' => View::POS_END,
//    ];
    
    public $css = [
        'css/elastislide.css'
    ];
    
    public function init() {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dists';
        parent::init();
    }

}