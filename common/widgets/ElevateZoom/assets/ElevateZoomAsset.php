<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 28.05.2018
 * Time: 20:08
 */

namespace common\widgets\ElevateZoom\assets;
use yii\web\AssetBundle;

class ElevateZoomAsset extends AssetBundle {
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $js = [
        'js/jquery.elevatezoom.js',
    ];

    public function init() {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dists';
        parent::init();
    }
}