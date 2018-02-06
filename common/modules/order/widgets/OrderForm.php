<?php
namespace common\modules\order\widgets;

use yii\helpers\Url;
use common\modules\order\models\Order;
use common\modules\order\models\PaymentType;
use common\modules\order\models\ShippingType;
use common\modules\order\models\Field;
use common\modules\order\models\FieldValue;
use yii;

class OrderForm extends \yii\base\Widget
{
    
    public $view = 'order-form/form';
    public $elements = [];
    
    public function init()
    {
        \common\modules\order\assets\OrderFormAsset::register($this->getView());
        
        return parent::init();
    }
    
    public function run()
    {
        $shippingTypesList = ShippingType::find()->orderBy('order DESC')->all();

        $shippingTypes = ['' => yii::t('order', 'Choose shipping type')];
        
        foreach($shippingTypesList as $sht) {
            if($sht->cost > 0) {
                $currency = yii::$app->getModule('order')->currency;
                $name = "{$sht->name} ({$sht->cost}{$currency})";
            } else {
                $name = $sht->name;
            }
            $shippingTypes[$sht->id] = $name;
        }
        
        $paymentTypes = ['' => yii::t('order', 'Choose payment type')];
        $paymentTypesList = PaymentType::find()->orderBy('order DESC')->all();
        
        foreach($paymentTypesList as $pt) {
            $paymentTypes[$pt->id] = $pt->name;
        }
        
        $fieldFind = Field::find()->orderBy('order DESC');
        
        $fieldValueModel = new FieldValue;
    
        $orderModel = new Order;
        
        if(empty($orderModel->shipping_type_id) && $orderShippingType = yii::$app->session->get('orderShippingType')) {
            if($orderShippingType > 0) {
                $orderModel->shipping_type_id = (int)$orderShippingType;
            }
        }
        
        $this->getView()->registerJs("shop.orderForm.updateShippingType = '".Url::toRoute(['/order/tools/update-shipping-type'])."';");
        
        return $this->render($this->view, [
            'orderModel' => $orderModel,
            'fieldFind' => $fieldFind,
            'paymentTypes' => $paymentTypes,
            'elements' => $this->elements,
            'shippingTypes' => $shippingTypes,
            'shippingTypesList' => $shippingTypesList,
            'fieldValueModel' => $fieldValueModel,
        ]);
    }

}
