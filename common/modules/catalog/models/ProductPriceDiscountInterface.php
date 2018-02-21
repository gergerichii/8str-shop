<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.01.2018
 * Time: 9:54
 */

namespace common\modules\catalog\models;

interface ProductPriceDiscountInterface {
    public function __toString();

    public function getActiveTo();
    public function getValue();
}