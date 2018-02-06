<?php
namespace common\modules\order\assets;

use yii\web\AssetBundle;

class OneClickAsset extends AssetBundle
{
    public $depends = [
        'common\modules\order\assets\Asset'
    ];

    public $js = [
        'js/oneclick.js',
    ];

    public $css = [
        'css/oneclick.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
