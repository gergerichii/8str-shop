<?php
namespace common\models\forms;

use common\models\entities\UserAddresses;
use common\services\UserService;
use common\base\forms\CompositeForm;
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
        parent::__construct($config);
        
        if ($this->scenario !== self::SCENARIO_GUEST) {
            $this->userAddresses = new UserAddresses(['scenario' => UserAddresses::SCENARIO_REGISTER]);
            $userScenario = User::SCENARIO_REGISTER;
        } else {
            $userScenario = User::SCENARIO_REGISTER_GUEST;
        }
        $this->user = new User(['scenario' => $userScenario]);
    }
    
    public function setScenario($value) {
        parent::setScenario($value);
        if (isset($this->user)) {
            if ($value === self::SCENARIO_GUEST) {
                $this->user->setScenario(User::SCENARIO_REGISTER_GUEST);
                $this->enabledForms = array_diff($this->includedForms(), ['userAddress']);
            } else {
                $this->user->setScenario(User::SCENARIO_REGISTER);
                $this->enabledForms = null;
            }
        }
    }
    
    /**
     * @return array
     */
    public function scenarios() {
        return array_merge(parent::scenarios(), [self::SCENARIO_GUEST => []]);
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
    
    public function includedForms() {
        return ['user', 'userAddresses'];
    }
}
