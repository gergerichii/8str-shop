<?php
namespace common\modules\cart\widgets;

use yii\helpers\Html;

class ElementPrice extends \yii\base\Widget
{
    public $model = NULL;
    public $cssClass = NULL;
    public $htmlTag = 'span';
    
    public function init()
    {
        parent::init();
        return true;
    }
    
    public function run()
    {
        $price = \Yii::$app->formatter->asCurrency($this->model->price);
        return Html::tag($this->htmlTag, $price, [
            'class' => "shop-cart-element-price{$this->model->getId()} {$this->cssClass}",
        ]);
    }
}