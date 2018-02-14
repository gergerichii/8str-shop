<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 14.02.2018
 * Time: 17:37
 */

namespace common\modules\order\forms\frontend;
use common\models\forms\LoginForm;

class Step1Form extends LoginForm {
    public $orderMode;
}