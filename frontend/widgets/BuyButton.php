<?php
namespace frontend\widgets;

use dvizh\cart\assets\WidgetAsset;
use dvizh\cart\interfaces\CartElement;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class BuyButton extends Widget
{
    public $text = NULL;
    /** @var \common\base\models\cart\CartElement */
    public $model = NULL;
    public $count = 1;
    public $price = false;
    public $description = '';
    public $cssClass = NULL;
    public $htmlTag = 'a';
    public $options = null;
    public $addElementUrl = '/cart/element/create';

    public function init()
    {
        parent::init();

        WidgetAsset::register($this->getView());

        if ($this->options === NULL) {
            $this->options = (object)[];
        }
        
        if ($this->text === NULL) {
            $this->text = Yii::t('cart', 'Buy');
        }

        if ($this->cssClass === NULL) {
            $this->cssClass = 'btn btn-success';
        }
        
        return true;
    }

    public function run()
    {
        if (!is_object($this->model) | !$this->model instanceof CartElement) {
            return false;
        }

        $model = $this->model;
        return Html::tag($this->htmlTag, $this->text, [
            'href' => Url::toRoute($this->addElementUrl),
            'class' => "dvizh-cart-buy-button dvizh-cart-buy-button{$this->model->getCartId()} {$this->cssClass}",
            'data-id' => $model->getCartId(),
            'data-url' => Url::toRoute($this->addElementUrl),
            'data-role' => 'cart-buy-button',
            'data-count' => $this->count,
            'data-price' => (int)$this->price,
            'data-options' => json_encode($this->options),
            'data-description' => $this->description,
            'data-model' => $model::className()
        ]);
    }
}