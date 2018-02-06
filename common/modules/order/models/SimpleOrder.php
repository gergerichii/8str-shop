<?php
namespace common\modules\order\models;

use yii;

class SimpleOrder extends \common\modules\order\models\Order
{

    public function rules()
    {
        return [
            [['phone', 'email'], 'string', 'skipOnEmpty' => true],
        ];
    }

    public function beforeValidate()
    {
        $this->client_name = "";
        $this->email = "";
        $this->phone = "";
        $this->time = time();

        return parent::beforeValidate();
    }
}
