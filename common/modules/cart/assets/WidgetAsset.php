<?php
namespace common\modules\cart\assets;

use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public $js = [
        'js/scripts.js',
    ];
    
    public $css = [
        'css/styles.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }
}
