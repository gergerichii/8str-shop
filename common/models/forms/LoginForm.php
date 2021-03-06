<?php
namespace common\models\forms;

use Yii;
use yii\base\Model;
use common\models\entities\User;

/**
 * Login form
 *
 * @property-read $user User
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Такого сочетания Email/Логина и Пароля не зарегистрировано');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->getUser()->status > User::STATUS_GUEST) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } elseif ($this->getUser()->status <= User::STATUS_GUEST) {
            $this->addError('password', 'Такого сочетания Email/Логина и Пароля не зарегистрировано');
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            if (strpos($this->username, '@') > 0) {
                $this->_user = User::findByEmail($this->username);
            } else {
                $this->_user = User::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}
