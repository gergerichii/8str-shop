<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.01.2018
 * Time: 9:50
 */

namespace common\models\entities;
interface ProductPriceInterface {
    public function __toString();
    public function getValue();
}