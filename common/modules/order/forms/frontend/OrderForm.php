<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.02.2018
 * Time: 11:39
 */

namespace common\modules\order\forms\frontend;

use common\models\entities\User;
use common\models\forms\LoginForm;
use common\models\forms\SignupForm;
use common\modules\order\models\Order;
use elisdn\compositeForm\CompositeForm;

class OrderForm extends CompositeForm {
    
    public const ORDER_MODE_REGISTER = 'register';
    public const ORDER_MODE_LOGIN = 'login';
    public const ORDER_MODE_GUEST = 'guest';
    
    public const SCENARIO_STEPS = [
        1 => [
            'name' => 'authorization',
            'models' => [
                'loginForm',
                'signUpForm' => 'guest',
            ],
        ],
        2 => [
            'name' => 'deliveryInformation',
            'models' => [
                'addressForm',
                'deliveryMethodForm',
            ],
        ],
        3 => [
            'name' => 'paymentMethod',
            'models' => [
                'paymentMethodForm',
            ],
        ],
        4 => [
            'name' => 'confirmOrder',
            'models' => [
                'cartForm',
            ],
        ],
        
    ];
    
    public $orderMode = self::ORDER_MODE_REGISTER;
    public $orderStep;
    
    
    public function __construct(array $config = []) {
        
        $this->loginForm = new LoginForm();
        $this->signupForm = new SignupForm();
        
        $this->orderStep = \Yii::$app->session->get(
            'orderStep',
            \Yii::$app->user->isGuest ? 1 : 2
        );
        $this->scenario = self::SCENARIO_STEPS[$this->orderStep]['name'];
        
        parent::__construct($config);
    }
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['loginForm', 'signupForm'];
    }
}