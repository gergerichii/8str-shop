<?php
namespace common\modules\order\models;

use yii;

class Payment extends \common\base\models\BaseActiveRecord
{
    public static function tableName()
    {
        return '{{%order_payment}}';
    }

    public function rules()
    {
        return [
            [['order_id', 'amount', 'description', 'date', 'payment_type_id', 'ip'], 'required'],
            [['order_id', 'user_id', 'payment_type_id'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 55],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => yii::t('order', 'Order'),
            'amount' => yii::t('order', 'Amount'),
            'description' => yii::t('order', 'Description'),
            'user_id' => yii::t('order', 'User'),
            'date' => yii::t('order', 'Date'),
            'payment_type_id' => yii::t('order', 'Payment type'),
            'ip' => yii::t('order', 'IP'),
        ];
    }
    
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }
    
    public function getPayment()
    {
        return $this->hasOne(PaymentType::class, ['id' => 'payment_type_id']);
    }
}
