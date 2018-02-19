<?php

namespace common\modules\order\controllers;

use common\modules\order\forms\frontend\Step1Form;
use common\modules\order\forms\frontend\Step2Form;
use common\modules\order\Module as OrderModule;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class DefaultController
 *
 * @package common\modules\order\controllers
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $step = \Yii::$app->user->isGuest ? 1 : 2;
        
        $step1form = new Step1Form();
        
        if ($orderSteps = \Yii::$app->session->get('order_steps')) {
            $step1form->setAttributes($orderSteps['step1']);
            $step = $orderSteps['step'];
        } else {
            $orderSteps = [];
            for ($i=1; $i <= 6; $i++) {
                $orderSteps['step'] = $step;
                $orderSteps["step{$i}"] = [];
            }
        }
        $step2form = new Step2Form($orderSteps['step2']);
        
        // Если пришли данные формы первого шага
        if ($step1form->load($request->post())) {
            // Если продолжаем как гость или если нормально логинимся, то шаг 2
            if (
                $step1form->orderMode === OrderModule::ORDER_MODE_GUEST
                || (
                    $step1form->orderMode === OrderModule::ORDER_MODE_LOGIN
                    && $step1form->login()
                )
            ) {
                $step = 2;
                $step2form->scenario = $step1form->orderMode;
                
                $orderSteps['step'] = $step;
                $orderSteps['step1'] = $step1form->getAttributes();
            } elseif ($step1form->errors) {
                \Yii::$app->session->setFlash('modelErrors', $step1form->errors);
                \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                    \Yii::$app->response->setStatusCode(207, 'error');
                });
            }
        }
        
        \Yii::$app->session->set('order_steps', $orderSteps);
        
        return $this->render('index', compact(['step1form', 'step2form', 'step']));
    }

}
