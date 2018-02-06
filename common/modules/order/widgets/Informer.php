<?php
namespace common\modules\order\widgets;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use common\modules\order\models\Order;
use common\modules\order\models\Element;
use yii\helpers\ArrayHelper;

class Informer extends \yii\base\Widget
{
    public $view = 'informer';
    public $elements = [];
    
    public function init()
    {
        parent::init();

        \common\modules\order\assets\Asset::register($this->getView());

        return true;
    }
    
    public function run()
    {
        $today = yii::$app->order->getStatByDate(date('Y-m-d'));
        
        $inMonth = yii::$app->order->getStatInMoth();
        
        $byMonth = yii::$app->order->getStatByDatePeriod(date('Y-m-d H:i:s', time()-(86400*30)), date('Y-m-d H:i:s'));
        
        $byOldMonth = yii::$app->order->getStatByDatePeriod(date('Y-m-d H:i:s', time()-(86400*60)), date('Y-m-d H:i:s', time()-(86400*30)));
        
        return $this->render($this->view, [
            'today' => $today,
            'inMonth' => $inMonth,
            'byMonth' => $byMonth,
            'byOldMonth' => $byOldMonth,
        ]);
    }
}
