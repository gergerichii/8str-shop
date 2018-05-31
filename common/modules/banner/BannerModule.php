<?php

namespace common\modules\banner;

use common\modules\baseModule\BaseModule;

/**
 * banner module definition class
 */
class BannerModule extends BaseModule
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\banner\controllers';
    
    /**
     * @inheritdoc
     */
    public
    function init() {
        parent::init();
        // custom initialization code goes here
    }
}
