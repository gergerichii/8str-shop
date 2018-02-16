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
        // Если первый шаг и пришли данные формы первого шага
        if ($step == 1 && $step1form->load($request->post())) {
            // Если продолжаем как гость или если нормально логинимся, то шаг 2
            if (
                $step1form->orderMode === OrderModule::ORDER_MODE_GUEST ||
                (
                    $step1form->orderMode === OrderModule::ORDER_MODE_LOGIN
                    && $step1form->login()
                )
            ) {
                $step = 2;
            } elseif ($step1form->errors) {
                \Yii::$app->session->setFlash('modelErrors', $step1form->errors);
                \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                    \Yii::$app->response->setStatusCode(207, 'error');
                });
            } else {
                //Готовим форму регистрации
            }
        }
        $step2form = new Step2Form();
        
        return $this->render('index', compact(['step1form', 'step2form', 'step']));
    }

}
