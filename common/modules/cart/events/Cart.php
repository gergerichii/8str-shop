<?php
namespace common\modules\cart\events;

use yii\base\Event;

class Cart extends Event
{
    public $cart;
    public $cost;
    public $count;
}