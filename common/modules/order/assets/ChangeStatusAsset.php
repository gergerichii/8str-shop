<?php
namespace common\modules\order\assets;

use yii\web\AssetBundle;

class ChangeStatusAsset extends AssetBundle
{
    public $depends = [
        'common\modules\order\assets\Asset'
    ];

    public $js = [
        'js/changestatus.js',
    ];

    public $css = [
        
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
