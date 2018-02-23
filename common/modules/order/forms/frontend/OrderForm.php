<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.02.2018
 * Time: 11:39
 */

namespace common\modules\order\forms\frontend;

use common\models\entities\User;
use common\modules\order\models\Order;
use elisdn\compositeForm\CompositeForm;

class OrderForm extends CompositeForm {
    
    public const ORDER_MODE_REGISTER = 'register';
    public const ORDER_MODE_LOGIN = 'login';
    public const ORDER_MODE_GUEST = 'guest';
    
    public const SCENARIO_STEP_1 = 'step1';
    public const SCENARIO_STEP_2 = 'step2';
    public const SCENARIO_STEP_3 = 'step3';
    public const SCENARIO_STEP_4 = 'step4';
    public const SCENARIO_STEP_5 = 'step5';
    
    public $orderMode = self::ORDER_MODE_REGISTER;
    
    
    public function __construct(array $config = []) {
        
        $this->user = new User();
        $this->order = new Order();
        
        parent::__construct($config);
    }
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return [];
    }
}