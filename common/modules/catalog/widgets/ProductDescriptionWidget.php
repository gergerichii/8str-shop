<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07.06.2018
 * Time: 13:13
 */

namespace common\modules\catalog\widgets;
use yii\base\Widget;

class ProductDescriptionWidget extends Widget {
    public $adaptiveTabsConfig = [];
    public $model;
    
    /**
     * @return string|void
     */
    public function run() {
        return $this->render('productDescriptionWidget', ['model' => $this->model, 'tabsConfig' => $this->adaptiveTabsConfig]);
    }
}