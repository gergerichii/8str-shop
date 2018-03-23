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
        
        if ($orderForm->load($request->post()) && $orderForm->validate()) {
            switch ($orderForm->orderStep) {
                case 1:
                    if ($orderForm->orderMode === OrderForm::ORDER_MODE_REGISTER) {
                        $url = Url::toRoute(['/site/signup']);
                        return $this->redirect($url);
                    } elseif ($orderForm->orderMode === OrderForm::ORDER_MODE_GUEST) {
                        $result = $orderForm->signupForm->signup(false);
                        $orderForm->orderStep = 2;
                    } elseif ($orderForm->orderMode === OrderForm::ORDER_MODE_LOGIN) {
                        $result = $orderForm->loginForm->login();
                        $orderForm->orderStep = 2;
                    }
                    break;
                case 2:
                
            }
        } else {
            $errors = $orderForm->getErrorSummary(true);
        }
        
        $ser = serialize($orderForm);
        \Yii::$app->session->set('orderForm', $ser);
        
        return $this->render('index', ['orderForm' => $orderForm]);
    }

}
