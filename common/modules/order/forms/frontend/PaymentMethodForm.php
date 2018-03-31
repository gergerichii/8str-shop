<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29.03.2018
 * Time: 11:33
 */

namespace common\modules\order\forms\frontend;
use yii\base\Model;

class PaymentMethodForm extends Model {
    
    public const METHOD_CASH = 'cash';
    public const METHOD_NON_CASH = 'non-cash';
    public const METHOD_TRANSFER = 'transfer';
    
    public const PAYMENT_METHODS = [
        self::METHOD_CASH => 'Наличными',
        self::METHOD_NON_CASH => 'Безналичный расчет',
        self::METHOD_TRANSFER => 'Перевод на карту сбербанка',
    ];
    
    public $methodId = self::METHOD_CASH;
    /** @var string  */
    public $requisites = '';
    
    public function rules() {
        return [
            ['methodId', 'required'],
            ['methodId', 'in', 'range' => array_keys($this->getPaymentMethods())],
            ['requisites', 'safe'],
        ];
    }
    
    public function getPaymentMethods() {
        return self::PAYMENT_METHODS;
    }
}