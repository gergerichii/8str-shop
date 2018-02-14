<?php

namespace common\modules\order\controllers;

use common\modules\order\forms\frontend\Step1Form;
use common\modules\order\models\Order;

class DefaultController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $order = new Order();
        
        $request = \Yii::$app->request;
        $step = 1;
        $step1form = new Step1Form();
        if (!\Yii::$app->user->isGuest || $step1form->load($request->post())) {
            $step = 2;
        }
        
        
        
        return $this->render('index', compact(['step1form', 'step']));
    }

}
