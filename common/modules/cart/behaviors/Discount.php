<?php
namespace common\modules\cart\behaviors;

use yii\base\Behavior;
use common\modules\cart\CartService;

class Discount extends Behavior
{
    public $percent = 0;

    public function events()
    {
        return [
            CartService::EVENT_CART_COST => 'doDiscount'
        ];
    }

    public function doDiscount($event)
    {
        if($this->percent > 0 && $this->percent <= 100 && $event->cost > 0) {
            $event->cost = $event->cost-($event->cost*$this->percent)/100;
        }

        return $this;
    }
}
