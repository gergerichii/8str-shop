<?php

namespace common\modules\order\controllers;

use common\models\entities\User;
use common\models\entities\UserAddresses;
use common\modules\order\forms\frontend\OrderForm;
use common\modules\order\forms\frontend\Step1Form;
use common\modules\order\forms\frontend\Step2Form;
use common\modules\order\Module as OrderModule;
use Throwable;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DefaultController
 *
 * @package common\modules\order\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $orderForm = new OrderForm();
        return $this->render('index', ['orderForm' => $orderForm]);
    }

}
