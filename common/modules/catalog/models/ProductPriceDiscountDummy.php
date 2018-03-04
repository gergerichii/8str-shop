<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.01.2018
 * Time: 10:42
 */

namespace common\modules\catalog\models;

use yii\base\Model;

/**
 *
 * @property int  $value
 * @property void $activeTo
 */
class ProductPriceDiscountDummy extends Model implements ProductPriceDiscountInterface
{
    public function __toString(){
        return '';
    }

    public function getValue(){
        return 0;
    }

    public function getActiveTo(){
        // TODO: Implement getActiveTo() method.
    }
}