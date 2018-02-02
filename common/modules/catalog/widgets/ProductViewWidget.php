<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 30.01.2018
 * Time: 23:02
 */

namespace common\modules\catalog\widgets;
use yii\widgets\DetailView;

/**
 * Class ProductViewWidget
 *
 * @package common\modules\catalog\widgets
 *
 * TODO: Довести до ума
 */
class ProductViewWidget extends DetailView {
    public $view = 'productMedium';
    
    public $model;
    
    public function run() {
        return $this->render('productMedium', ['model' => $this->model]);
    }
}