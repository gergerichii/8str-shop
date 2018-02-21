<?php

namespace common\modules\order\controllers;

use common\models\entities\User;
use common\modules\order\forms\frontend\Step1Form;
use common\modules\order\forms\frontend\Step2Form;
use common\modules\order\Module as OrderModule;
use yii\base\Exception;
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
        
        /* Достаем данные из сессии */
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
        
        // ШАГ 1
        if ($step1form->load($request->post())) {
            if ($step1form->orderMode === OrderModule::ORDER_MODE_LOGIN){
                $step1form->scenario = 'login';
                $ok = $step1form->login();
            } else {
                $ok = $step1form->validate();
            }
            if ($ok) {
                $step = 2;
            }
        }
        
        if ($step1form->errors) {
            \Yii::$app->session->setFlash('modelErrors', $step1form->errors);
            \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                \Yii::$app->response->setStatusCode(207, 'error');
            });
        }
        
        // ШАГ 2
        $step2form = new Step2Form($orderSteps['step2']);
        $step2form->scenario = $step1form->orderMode;
        
        if ($step2form->load($request->post())) {
            if ($step2form->validate()) {
                $ok = true;
                if ($step1form->orderMode === OrderModule::ORDER_MODE_REGISTER) {
                    $user = new User();
                    $user->status = User::STATUS_ACTIVE;
                    try {
                        $user->setAttributes($step2form->getAttributes());
                        $user->generateAuthKey();
                        $ok &= $user->save();
                    } catch(Exception $e) {
                        \Yii::error($e->getMessage(), 'order.defaultController');
                        $ok = false;
                        \Yii::$app->session->setFlash('modelErrors', 'Произошла непредвиденная ошибка.');
                        \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                            \Yii::$app->response->setStatusCode(207, 'error');
                        });
                    }
                    
                    if ($ok) {
                        $ok = \Yii::$app->user->login($user, 3600 * 24 * 30);
                    }
                    if ($user->getErrors()) {
                        \Yii::$app->session->setFlash('error', $user->getErrorSummary(true));
                        \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                            \Yii::$app->response->setStatusCode(207, 'error');
                        });
                    }
                }
                
                if ($ok) {
                    $step = 3;
                }
            }
        }
        if ($step2form->errors) {
            \Yii::$app->session->setFlash('modelErrors', $step2form->errors);
            \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                \Yii::$app->response->setStatusCode(207, 'error');
            });
        }
        
        //ШАГ 3
        
        $orderSteps['step'] = $step;
        $orderSteps['step1'] = $step1form->getAttributes();
        $orderSteps['step2'] = $step2form->getAttributes();
        
        \Yii::$app->session->set('order_steps', $orderSteps);
        
        return $this->render('index', compact(['step1form', 'step2form', 'step']));
    }

}
