<?php

namespace common\modules\order\controllers;

use common\models\entities\User;
use common\models\entities\UserAddresses;
use common\modules\order\forms\frontend\OrderForm;
use common\modules\order\forms\frontend\Step1Form;
use common\modules\order\forms\frontend\Step2Form;
use common\modules\order\Module as OrderModule;
use common\services\UserService;
use Throwable;
use yii\helpers\Url;
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
     * @throws \Yii\base\Exception
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $session = \Yii::$app->session;
        $orderForm = ($session->has('orderForm')) ? unserialize($session->get('orderForm')) : new OrderForm();
        /** @var \common\modules\order\Module $orderModule */
        $orderModule = \Yii::$app->getModule('order');
        
        if ($orderForm->load($request->post()) && $orderForm->validate()) {
            if (intval($orderForm->orderStep) === 1 && $orderForm->orderMode === OrderForm::ORDER_MODE_REGISTER) {
                $url = Url::toRoute(['/site/signup']);
                return $this->redirect($url);
            } else {
                $orderModule->processOrder($orderForm);
            }
        } else {
            $errors = $orderForm->getErrorSummary(true);
            if ($errors) {
                \Yii::$app->session->setFlash('modelErrors', $errors);
                \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                    \Yii::$app->response->setStatusCode(207, 'error');
                });
            }
        }
        
        $ser = serialize($orderForm);
        \Yii::$app->session->set('orderForm', $ser);
        
        return $this->render('index', ['orderForm' => $orderForm]);
    }

}
