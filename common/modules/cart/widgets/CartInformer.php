<?php
namespace common\modules\cart\widgets;

use common\modules\cart\assets\WidgetAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class CartInformer extends Widget
{

    public $text = null;
    public $offerUrl = null;
    public $cssClass = null;
    public $htmlTag = null;
    public $showOldPrice = true;
    
    /**
     * @param array $config
     *
     * @return \common\modules\cart\widgets\CartInformer|\yii\base\Widget
     */
    public static function begin($config = []) {
        $widget = parent::begin($config);
        
        ob_start();
        
        return $widget;
    }
    
    /**
     * @return \common\modules\cart\widgets\CartInformer|\yii\base\Widget
     */
    public static function end() {
        $widget = array_pop(static::$stack);
        $widget->text = ob_get_clean();
        array_push(static::$stack, $widget);
        return parent::end();
    }
    
    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        WidgetAsset::register($this->getView());

        if ($this->offerUrl == NULL) {
            $this->offerUrl = Url::toRoute(["/cart/default/index"]);
        }
        
        if ($this->text === NULL) {
            $this->text = '{c} '. Yii::t('cart', 'on').' {p}';
        }
    }
    
    /**
     * @return string
     */
    public function run()
    {
        /** @var \common\modules\cart\CartService $cartService */
        $cartService = yii::$app->get('cartService');

        if($this->showOldPrice == false | $cartService->cost == $cartService->getCost(false)) {
            $this->text = str_replace(['{c}', '{p}'],
                ['<span class="shop-cart-count">'.$cartService->getCount().'</span>', '<strong class="shop-cart-price">'.$cartService->getCostFormatted().'</strong>'],
                $this->text
            );
        } else {
            $this->text = str_replace(['{c}', '{p}'],
                ['<span class="shop-cart-count">'.$cartService->getCount().'</span>', '<strong class="shop-cart-price"><s>'.round($cartService->getCost(false)).'</s>'.$cartService->getCostFormatted().'</strong>'],
                $this->text
            );
        }
        
        $ret = $this->text;
        
        if ($this->htmlTag) {
            $ret = Html::tag($this->htmlTag, $this->text, [
                'href' => $this->offerUrl,
                'class' => "shop-cart-informer {$this->cssClass}",
            ]);
        } else {
            $ret = str_replace('{link}', $this->offerUrl, $ret);
        }
        
        return $ret;
    }
}
