<?php
namespace common\modules\cart\widgets; 

use common\modules\cart\assets\WidgetAsset;
use common\modules\cart\interfaces\CartElement;
use yii\helpers\Url;
use yii\helpers\Html;

class ChangeCount extends \yii\base\Widget
{
    public $model = NULL;
    public $lineSelector = 'li'; //Селектор материнского элемента, где выводится элемент
    public $downArr = '⟨ ';
    public $upArr = ' ⟩';
    public $downArrCssClass = '';
    public $upArrCssClass = '';
    public $cssClass = 'shop-change-count';
    public $defaultValue = 1;
    public $showArrows = true;
    public $actionUpdateUrl = '/cart/element/update';
    public $customView = false; // for example '@frontend/views/custom/changeCountLayout'
    public $tag = 'span';

    public function init()
    {
        parent::init();

        WidgetAsset::register($this->getView());
        
        return true;
    }

    public function run()
    {
        if($this->showArrows) {
            $downArr = Html::a($this->downArr, '#', ['class' => 'shop-arr shop-downArr ' . $this->downArrCssClass]);
            $upArr = Html::a($this->upArr, '#', ['class' => 'shop-arr shop-upArr ' . $this->upArrCssClass]);
        } else {
            $downArr = $upArr = '';
        }
        
        if(!$this->model instanceof CartElement) {
            $input = Html::activeTextInput($this->model, 'count', [
                'type' => 'number',
                'class' => 'shop-cart-element-count',
                'data-role' => 'cart-element-count',
                'data-line-selector' => $this->lineSelector,
                'data-id' => $this->model->getId(),
                'data-href' => Url::toRoute($this->actionUpdateUrl),
            ]);
        } else {
            $input = Html::input('number', 'count', $this->defaultValue, [
                'class' => 'shop-cart-element-before-count',
                'data-line-selector' => $this->lineSelector,
                'data-id' => $this->model->getCartId(),
            ]);
        }
        
        if ($this->customView) {
            return $this->render($this->customView, [
                'model' => $this->model,
                'defaultValue' => $this->defaultValue,
            ]);
        } else {
            return Html::tag($this->tag, $downArr.$input.$upArr, ['class' => $this->cssClass]);
        }
    }
}
