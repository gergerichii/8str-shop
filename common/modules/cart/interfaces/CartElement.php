<?php
namespace common\modules\cart\interfaces;

interface CartElement
{
    public function getCartId();

    public function getCartName();

    public function getCartPrice();
    
    public function getCartOptions();
}
