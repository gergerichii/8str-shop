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

/**
 * Class OrderForm
 *
 * @package common\modules\order\forms\frontend
 *
 * @property LoginForm $loginForm
 * @property SignupForm $signupForm
 *
 */
class OrderForm extends CompositeForm {
    
    public const ORDER_MODE_REGISTER = 'register';
    public const ORDER_MODE_LOGIN = 'login';
    public const ORDER_MODE_GUEST = 'guest';
    
    public const SCENARIO_STEPS = [
        1 => [
            'name' => 'authorization',
            'title' => 'Способ авторизации',
        ],
        2 => [
            'name' => 'deliveryInformation',
            'title' => 'Информация по доставке',
        ],
//        3 => [
//            'name' => 'paymentMethod',
//            'title' => 'Способ оплаты',
//            'models' => [
//                'paymentMethodForm',
//            ],
//        ],
//        4 => [
//            'name' => 'confirmOrder',
//            'title' => 'Подтверждение заказа',
//            'models' => [
//                'cartForm',
//            ],
//        ],
//
    ];
    
    public $orderMode = self::ORDER_MODE_REGISTER;
    public $orderStep;
    
    protected $__forms = [];
    
    public function __construct(array $config = []) {
        
        $this->loginForm = new LoginForm();
        $this->signupForm = new SignupForm();
        $this->signupForm->setScenario(SignupForm::SCENARIO_GUEST);
        
        $this->orderStep = \Yii::$app->session->get(
            'orderStep',
            \Yii::$app->user->isGuest ? 1 : 2
        );
@        $this->scenario = self::SCENARIO_STEPS[$this->orderStep]['name'];
        
        parent::__construct($config);
    }
    
    public function rules() {
        return [
            ['orderMode', 'in', 'range' => [self::ORDER_MODE_REGISTER, self::ORDER_MODE_LOGIN, self::ORDER_MODE_GUEST]],
        ];
    }
    
    public function scenarios() {
        return [
            'default' => ['orderMode'],
            self::SCENARIO_STEPS[1]['name'] => ['orderMode'],
            self::SCENARIO_STEPS[2]['name'] => ['orderMode'],
//            self::SCENARIO_STEPS[3]['name'] => ['orderMode'],
//            self::SCENARIO_STEPS[4]['name'] => ['orderMode'],
        ];
    }
    
    public function validate($attributeNames = NULL, $clearErrors = TRUE) {
        $this->__forms = $this->_forms;
        switch ($this->orderMode) {
            case self::ORDER_MODE_LOGIN:
                $this->_forms = [
                    'loginForm' => $this->__forms['loginForm'],
                ];
                break;
            case self::ORDER_MODE_GUEST:
                $this->_forms = [
                    'signupForm' => $this->__forms['signupForm'],
                ];
                break;
            default:
                $this->_forms = [];
        }

        $ret =  parent::validate($attributeNames, $clearErrors);
        
        $this->_forms = $this->__forms;
        
        return $ret;
    }
    
    public function setScenario($value) {
        parent::setScenario($value);
    }
    
    
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['loginForm', 'signupForm'];
    }
}