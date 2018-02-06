<?php

namespace common\modules\order\widgets;

use common\modules\order\models\PaymentType;
use yii\helpers\ArrayHelper;
use common\modules\order\models\Order;
use yii;

class ReportPaymentTypes extends \yii\base\Widget
{
    public $dateStart = null;
    public $dateStop = [];
    public $withAssigment = false;
    public $types = [];
    
    public function init()
    {
        if(!$this->dateStop) {
            $this->dateStop = date('Y-m-d H:i:s');
        }
        
        return parent::init();
    }

    public function run()
    {
        if($this->types) {
            $paymentTypes = ArrayHelper::map(PaymentType::find()->where(['id' => $this->types])->all(), 'id', 'name');
        } else {
            $paymentTypes = ArrayHelper::map(PaymentType::find()->all(), 'id', 'name');
        }

        $report = [];
        $hasReport = false;
        
        foreach($paymentTypes as $pid => $pname) {
           $query = Order::find()->where('date >= :dateStart AND date <= :dateStop', [':dateStart' => $this->dateStart, ':dateStop' => $this->dateStop]);
           $sum = $query->andWhere(['payment_type_id' => $pid])
                   ->distinct()
                   ->sum('cost');

           $report[$pname] = $sum;
           
           if($sum) {
               $hasReport = true;
           }
        }
        
        if(!$hasReport) {
            return '';
        }
        
        return $this->render('report-payment-types', [
            'report' => $report,
            'dateStart' => $this->dateStart,
            'dateStop' => $this->dateStop,
        ]);
    }
}
