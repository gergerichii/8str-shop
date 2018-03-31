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
use yii\web\Cookie;
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
     * @throws \Yii\base\Exception
     */
    public function actionIndex()
    {
        $request = \Yii::$app->request;
        $session = \Yii::$app->session;
        $newForm = !$session->has('orderForm');
        $orderForm = (!$newForm) ? unserialize($session->get('orderForm')) : new OrderForm();
        $orderForm->cartElements = \Yii::$app->get('cartService')->elements;
        if (!$orderForm->cartElements) {
            return $this->redirect(Url::toRoute('/cart/default/index'));
        }
        
        /** @var \common\modules\order\Module $orderModule */
        $orderModule = \Yii::$app->getModule('order');
        $result = false;
        /** TODO: Разобраться с глюком, почему, если в первом шаге пользователь существует, то после ввода нового мэйла, первая валидация сбойная а вторая нет */
        if ($orderForm->load($request->post()) && ($orderForm->validate() || $orderForm->validate())) {
            if (intval($orderForm->orderStep) === 1 && $orderForm->orderMode === OrderForm::ORDER_MODE_REGISTER) {
                $url = Url::toRoute(['/site/signup']);
                return $this->redirect($url);
            } else {
                $result = $orderModule->processOrder($orderForm);
            }
        } else {
            if ($newForm && intval($orderForm->orderStep) !== 1) {
                return $this->refresh();
            }
            $errors = $orderForm->getErrorSummary(true);
            if ($errors) {
                $orderForm->clearErrors();
                \Yii::$app->session->setFlash('modelErrors', $errors);
                \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                    \Yii::$app->response->setStatusCode(207, 'error');
                });
            }
        }
        
        
        if ($orderForm->orderModel) {
            if ($result) {
                if (intval($orderForm->orderStep) === 4) {
                    $orderId = $orderForm->orderModel->id;
                    \Yii::$app->session->remove('orderForm');
                    \Yii::$app->get('cartService')->truncate();
                    return $this->redirect(['thanks', 'orderId' => $orderId]);
                }
            } else {
                $errors = $orderForm->orderModel->getErrorSummary(true);
                if ($errors) {
                    $orderForm->orderModel->clearErrors();
                    \Yii::$app->session->setFlash('modelErrors', $errors);
                    \Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function(){
                        \Yii::$app->response->setStatusCode(207, 'error');
                    });
                }
            }
        }
        
        $ser = serialize($orderForm);
        \Yii::$app->session->set('orderForm', $ser);
        return $this->render('index', ['orderForm' => $orderForm]);
    }
    
    public function actionThanks($orderId) {
        return $this->render('thanks', ['orderId' => $orderId]);
    }

}
