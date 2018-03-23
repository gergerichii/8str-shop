<?php
namespace common\models\forms;

use common\models\entities\UserAddresses;
use common\services\UserService;
use elisdn\compositeForm\CompositeForm;
use common\models\entities\User;

/**
 * Signup form
 *
 * @property User user
 * @property UserAddresses[] userAddresses
 */
class SignupForm extends CompositeForm
{
    
    public const SCENARIO_GUEST = 'guest';
    
    public function __construct(array $config = []) {
        $this->user = new User();
        $this->user->setScenario(User::SCENARIO_REGISTER);
        
        $addresses = new UserAddresses();
        $addresses->scenario = UserAddresses::SCENARIO_REGISTER;
        $this->userAddresses = [$addresses];
        
        parent::__construct($config);
    }
    
    public function setScenario($value) {
        parent::setScenario($value);
        if ($value === self::SCENARIO_GUEST) {
            $this->user->setScenario(User::SCENARIO_REGISTER_GUEST);
        } else {
            $this->user->setScenario(User::SCENARIO_REGISTER);
        }
    }
    
    public function scenarios() {
        return [self::SCENARIO_DEFAULT => [], self::SCENARIO_GUEST => []];
    }
    
    /**
     * Signs user up.
     *
     * @param bool $validate
     *
     * @return User|null the saved model or null if saving fails
     * @throws \Yii\base\Exception
     */
    public function signup($validate = true)
    {
        return UserService::signUp($this, $validate) ? $this->user : null;
    }
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['user', 'userAddresses'];
    }
}
