<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26.04.2018
 * Time: 18:21
 */

namespace common\modules\catalog\models;
use corpsepk\yml\models\Offer;
use yii\helpers\ArrayHelper;

class YmlOffer extends Offer {
    public $deliveryOptions;
    
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
            ['deliveryOptions', 'safe']
        ]);
    }
}