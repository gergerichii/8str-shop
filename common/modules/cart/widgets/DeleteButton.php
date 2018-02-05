<?php
namespace common\modules\cart\widgets;

use common\modules\cart\assets\WidgetAsset;
use yii\helpers\Html;
use yii\helpers\Url;

class DeleteButton extends \yii\base\Widget
{
    public $text = NULL;
    public $model = NULL;
    public $cssClass = 'btn btn-danger';
    public $lineSelector = 'li';  //Селектор материнского элемента, где выводится элемент
    public $deleteElementUrl = '/cart/element/delete';

    /**
     * @param array $config
     *
     * @return \common\modules\cart\widgets\BuyButton|\yii\base\Widget
     */
    public static function begin($config = []) {
        $widget = parent::begin($config);
        
        ob_start();
        
        return $widget;
    }
    
    /**
     * @return \common\modules\cart\widgets\BuyButton|\yii\base\Widget
     */
    public static function end() {
        $widget = array_pop(static::$stack);
        $widget->text = ob_get_clean();
        array_push(static::$stack, $widget);
        return parent::end();
    }
    
    /**
     * @return bool|void
     */
    public function init()
    {
        parent::init();

        WidgetAsset::register($this->getView());

        if ($this->text == NULL) {
            $this->text = '╳';
        }

        return true;
    }

    public function run()
    {
        return Html::a($this->text, [$this->deleteElementUrl],
            [
                'data-url' => Url::toRoute($this->deleteElementUrl),
                'data-role' => 'cart-delete-button',
                'data-line-selector' => $this->lineSelector,
                'class' => 'shop-cart-delete-button ' . $this->cssClass,
                'data-id' => $this->model->getId()
            ]);
    }
}
