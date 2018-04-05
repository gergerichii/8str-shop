<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.02.2018
 * Time: 11:39
 */

namespace common\modules\order\forms\frontend;

use common\models\entities\User;
use common\models\entities\UserAddresses;
use common\models\forms\LoginForm;
use common\models\forms\SignupForm;
use common\base\forms\CompositeForm;
use common\modules\cart\models\Cart;

/**
 * Class OrderForm
 *
 * @package common\modules\order\forms\frontend
 *
 * @property LoginForm  $loginForm
 * @property SignupForm $signupForm
 * @property User       $user
 * @property UserAddresses[] $userAddresses
 * @property PaymentMethodForm $paymentForm
 * @property Cart[] $cartElements
 * @property array      $scenarioSteps
 * @property int        $orderStep
 * @property string     $orderMode
 *
 */
class OrderForm extends CompositeForm {

    public const ORDER_MODE_REGISTER = 'register';
    public const ORDER_MODE_LOGIN = 'login';
    public const ORDER_MODE_GUEST = 'guest';
    
    public const DELIVERY_METHOD_MKAD = 'mkad';
    public const DELIVERY_METHOD_OVER_MKAD = 'over_mkad';
    public const DELIVERY_METHOD_SELF = 'self';
    public const DELIVERY_METHOD_TK = 'tk';
    
    public const DELIVERY_METHODS = [
        self::DELIVERY_METHOD_MKAD => 'Доставка в пределах МКАД',
        self::DELIVERY_METHOD_OVER_MKAD => 'Доставка за пределы МКАД',
        self::DELIVERY_METHOD_SELF => 'Самовывоз из шоурума на Комсомольской площади',
        self::DELIVERY_METHOD_TK => 'Доставка до терминала транспортной компании',
    ];
    
    public const DELIVERY_METHODS_PRICES = [
        self::DELIVERY_METHOD_MKAD => 350,
        self::DELIVERY_METHOD_OVER_MKAD => 600,
        self::DELIVERY_METHOD_TK => 350,
        self::DELIVERY_METHOD_SELF => 0,
    ];
    
    public const SCENARIO_STEPS = [
        1 => [
            'name' => 'authorization',
            'title' => 'Способ авторизации',
        ],
        2 => [
            'name' => 'deliveryInformation',
            'title' => 'Информация по доставке',
        ],
        3 => [
            'name' => 'paymentMethod',
            'title' => 'Способ оплаты',
        ],
        4 => [
            'name' => 'confirmOrder',
            'title' => 'Подтверждение заказа',
        ],
    ];
    
    /** @var int */
    public $orderStep = 1;
    /** @var string  */
    public $orderMode = self::ORDER_MODE_REGISTER;
    /** @var string  */
    public $deliveryMethod = self::DELIVERY_METHOD_SELF;
    /** @var string  */
    public $deliveryComment = '';
    /** @var int  */
    public $deliveryAddressId = 0;
    /** @var \common\modules\order\models\TemporaryOrder */
    public $orderModel;
    
    
    /**
     * OrderForm constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = []) {
        
        parent::__construct($config);
        
        $this->loginForm = new LoginForm();
        $this->signupForm = new SignupForm(['scenario' => SignupForm::SCENARIO_GUEST]);

        if ($this->orderMode === self::ORDER_MODE_LOGIN) {
            $this->enabledForms = ['loginForm'];
        } elseif ($this->orderMode === self::ORDER_MODE_GUEST) {
            $this->enabledForms = ['signupForm'];
        }
        
        $this->userAddresses = [new UserAddresses(['scenario' => UserAddresses::SCENARIO_REGISTER])];
        if (\Yii::$app->user->isGuest) {
            $this->orderStep = 1;
            $this->user = new User(['scenario' => User::SCENARIO_REGISTER_GUEST]);
        } else {
            $this->user = \Yii::$app->user->identity;
            $this->orderStep = 2;
            $this->enabledForms = array_merge((array)$this->enabledForms, ['userAddresses']);
            $this->userAddresses = array_merge($this->userAddresses, $this->user->userAddresses);
        }
        
        $this->paymentForm = new PaymentMethodForm();
        $this->cartElements = \Yii::$app->get('cartService')->elements;
    }
    
    /**
     * @return array
     */
    public function rules() {
        return [
            ['orderMode', 'in', 'range' => [self::ORDER_MODE_REGISTER, self::ORDER_MODE_LOGIN, self::ORDER_MODE_GUEST]],
            ['orderStep', 'integer'],
            ['orderStep', 'checkOrderStep'],
            [['orderMode', 'orderStep',], 'required'],
            ['deliveryMethod', 'in', 'range' => array_keys(self::DELIVERY_METHODS)],
            ['deliveryAddressId', 'checkDeliveryAddressId'],
            ['deliveryComment', 'safe'],
        ];
    }
    
    public function scenarios() {
        $ret = parent::scenarios();
        if ($this->orderStep < 2) {
            $ret['default'] = array_diff($ret['default'], ['deliveryMethod', 'deliveryAddressId']);
        }
        
        return $ret;
    }
    
    public function checkDeliveryAddressId($attr) {
        $this->$attr = intval($this->$attr);
        $res = intval($this->orderStep) < 2;
        $res |= $this->deliveryMethod === self::DELIVERY_METHOD_SELF;
        $res |= array_key_exists(intval($this->$attr), (array)
            $this->userAddresses);
        
        if (!$res) {
            $this->addError($attr, 'Не правильно выбран адрес доставки.');
        }
        return $res;
    }
    
    public function checkOrderStep($step): int {
        switch ($step) {
            case 2:
                if (\Yii::$app->user->isGuest) {
                    $this->addError('orderStep', 'Не пройдена авторизация для перехода к следующему шану');
                    return false;
                }
                break;
            case 3:
                if ($this->deliveryMethod !== 'self' && empty($this->deliveryAddressId)) {
                    $this->addError('orderStep', 'Не указан адрес доставки для перехода к следующему шагу');
                    return false;
                }
                break;
            default:
                return true;
        }
        
        return true;
    }
    
    public function load($data, $formName = NULL) {
        $this->enabledForms = null;
        return parent::load($data, $formName);
    }
    
    public function beforeValidate() {
        if ($this->orderMode === self::ORDER_MODE_LOGIN) {
            $this->enabledForms = array_diff($this->includedForms(), ['signupForm']);
        } elseif ($this->orderMode === self::ORDER_MODE_GUEST) {
            $this->enabledForms = array_diff($this->includedForms(), ['loginForm']);
        } else {
            $this->enabledForms = array_diff($this->includedForms(), ['loginForm', 'signupForm']);
        }
        
        if ($this->orderStep < 4) {
            $this->enabledForms = array_diff($this->enabledForms, ['cartElements']);
        }
        if ($this->orderStep < 3) {
            $this->enabledForms = array_diff($this->enabledForms, ['paymentForm']);
        }
        if ($this->orderStep < 2) {
            $this->enabledForms = array_diff($this->enabledForms, ['userAddresses', 'user']);
        }
        
        if (! \Yii::$app->user->isGuest) {
            $this->enabledForms = array_diff($this->enabledForms, ['user']);
        }
        return true;
    }
    
    /**
     * @param null $attributeNames
     * @param bool $clearErrors
     *
     * @return bool
     */
    public function validate($attributeNames = NULL, $clearErrors = TRUE) {
        $newAddresses = $this->userAddresses;
        $flag = intval($this->orderStep) < 2;
        $flag |= $this->deliveryMethod === self::DELIVERY_METHOD_SELF || intval($this->deliveryAddressId) !== 0;
        if ($flag) {
            $userAddress = $newAddresses[0];
            unset($newAddresses[0]);
            $this->userAddresses = $newAddresses;
        }
        
        $ret = parent::validate($attributeNames, $clearErrors);
        /** @noinspection PhpUndefinedVariableInspection */
        if ($flag) {
            /** @noinspection PhpUndefinedVariableInspection */
            $newAddresses[0] = $userAddress;
            $this->userAddresses = $newAddresses;
        }
        
        return $ret;
    }
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    public function includedForms()
    {
        return ['loginForm', 'signupForm', 'user', 'userAddresses', 'paymentForm', 'cartElements'];
    }
    
    /**
     * @return array
     */
    public function getScenarioSteps() {
        return self::SCENARIO_STEPS;
    }
}