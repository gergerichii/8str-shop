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
 * @property UserAddresses[] addresses
 */
class SignupForm extends CompositeForm
{
    
    public function __construct(array $config = []) {
        $this->user = new User();
        $this->user->setScenario(User::SCENARIO_REGISTER);
        
        $this->userAddresses = new UserAddresses();
        $this->userAddresses->scenario = UserAddresses::SCENARIO_REGISTER;
        
        parent::__construct($config);
    }
    
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        return UserService::signUp($this) ? $this->user : null;
    }
    
    /**
     * @return array of internal forms like ['meta', 'values']
     */
    protected function internalForms()
    {
        return ['user', 'userAddresses'];
    }
}
