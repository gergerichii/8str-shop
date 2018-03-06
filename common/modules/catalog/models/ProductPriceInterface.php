<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.01.2018
 * Time: 9:50
 */

namespace common\modules\catalog\models;

interface ProductPriceInterface {
    public function __toString();
    public function getValue();
}