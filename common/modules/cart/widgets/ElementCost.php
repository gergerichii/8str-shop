<?php
namespace common\modules\cart\widgets;

use yii\helpers\Html;

class ElementCost extends \yii\base\Widget
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
        $cost = \Yii::$app->formatter->asCurrency($this->model->getCost());
        return Html::tag($this->htmlTag, $cost, [
            'class' => "shop-cart-element-cost{$this->model->getId()} {$this->cssClass}",
        ]);
    }
}