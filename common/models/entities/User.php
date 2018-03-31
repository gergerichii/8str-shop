<?php
namespace common\models\entities;

use common\base\models\BaseActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\base\Security;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @package common\models\entities
 *
 * User model
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $login
 * @property string $company
 * @property string $phone_number
 * @property boolean $agree_to_news
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $authKey
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property UserAddresses[] $userAddresses
 * @property string $password password
 * @property string       $addresses [json]
 */
class User extends BaseActiveRecord implements IdentityInterface {
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_GUEST = 1;

    const SCENARIO_REGISTER_CONSOLE = 'register_console';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_REGISTER_GUEST = 'guest';
    
    const ADDRESSES_TEMPLATE = [
        ''
    ];
    
    /** @var string */
    public $password;
    /** @var string */
    public $password_confirm;
    /** @var boolean */
    public $privacy_agree = false;
    
    /**
     * Is active
     * @return bool
     */
    public function isActive() {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        $scenarios = parent::scenarios();
        // The scenario for addition admin for migration
        $scenarios[self::SCENARIO_REGISTER_CONSOLE] = [
            'id', 'username', 'password_hash', 'password_reset_token', 'email', 'company', 'phone_number',
            'auth_key', 'status', 'created_at', 'updated_at', 'agree_to_news', 'addresses',
        ];
        $scenarios[self::SCENARIO_REGISTER] = [
            'first_name', 'last_name', 'username', 'password', 'password_confirm', 'password_hash', 'email',
            'company', 'phone_number', 'auth_key', 'status', 'created_at', 'updated_at', 'agree_to_news', 'privacy_agree',
            'addresses',
        ];
        $scenarios[self::SCENARIO_REGISTER_GUEST] = [
            'first_name', 'last_name', 'password_hash', 'email',
            'phone_number', 'auth_key', 'status', 'created_at', 'updated_at', 'agree_to_news', 'privacy_agree', 'addresses',
        ];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
    
            [['first_name', 'last_name', 'username'], 'trim'],
            [['first_name', 'last_name', 'username'], 'required'],
            [['first_name', 'last_name', 'username'], 'string', 'min' => 2, 'max' => 255],

            [
                'username', 'unique', 'targetClass' => '\common\models\entities\User',
                'targetAttribute' => 'username',
                'message' => 'Такой пользователь уже зарегистрирован'
            ],

            [['password', 'password_confirm'], 'trim'],
            [['password', 'password_confirm'], 'required'],
            [['password', 'password_confirm'], 'string', 'min' => 6, 'max' => 255],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'validateEmail','on' => [self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_GUEST]],

            ['phone_number', 'trim'],
            ['phone_number', 'required'],
            ['phone_number', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            ['phone_number', 'string', 'min' => 9],

            [['company'], 'trim'],
            [['company'], 'string', 'min' => 3, 'max' => 255],

            [['status', 'created_at', 'updated_at', 'id'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED], 'on' => [self::SCENARIO_REGISTER]],

            [['agree_to_news', 'privacy_agree'], 'boolean'],
            ['agree_to_news', 'default', 'value' => true],
            ['privacy_agree', 'compare', 'compareValue' => true, 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_GUEST]],
            
            ['addresses', 'default', 'value' => '[]'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'username' => 'Логин',
            'email' => 'Email',
            'phone_number' => 'Телефон',
            'company' => 'Организация',
            'password' => 'Пароль',
            'password_confirm' => 'Пароль повтор',
            'agree_to_news' => 'Согласие на новостную рассылку',
            'privacy_agree' => 'Я осзнакомлен с политикой конфеденциальности и согласен на обработку персональных данных.',
            'status' => 'Статус',
            'created_at' => 'Зарегистрирован',
            'updated_at' => 'Обновлен',
        ];
    }
    
    public function validateEmail() {
        $existsUser = self::findByEmail($this->email);
        if ($existsUser) {
            if ($this->scenario === self::SCENARIO_REGISTER_GUEST && $existsUser->status === self::STATUS_GUEST) {
                return true;
            } else {
                $this->addError('email', 'Пользователь с таким Email адресом уже зарегистрирован.');
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by email
     *
     * @param string $email
     *
     * @param int    $status
     *
     * @return static|null
     */
    public static function findByEmail($email, $status = self::STATUS_ACTIVE)
    {
        return static::findOne(['email' => $email, 'status' => $status]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     *
     * @throws yii\base\Exception
     */
    public function setPassword($password = null)
    {
        if(is_null($password)) {
            $password = ($this->scenario === self::SCENARIO_REGISTER_GUEST) ?
                Yii::$app->security->generateRandomString() :
                $this->password;
        }
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Alias for username
     *
     * @return string
     */
    public function getLogin() {
        return $this->username;
    }
    
    /**
     * Alias for username
     *
     * @param $value
     */
    public function setLogin($value) {
        $this->username = $value;
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     *
     * @throws yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAddresses() {
        return $this->hasMany(UserAddresses::class, ['user_id' => 'id']);
    }
    
    public function afterFind() {
        parent::afterFind();
        $this->privacy_agree = true;
    }
    
    public function __wakeup() {
        $this->refresh();
    }
}
