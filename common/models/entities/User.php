<?php
namespace common\models\entities;

use common\base\models\BaseActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * Class User
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
 * @property UserAddresses[] $addresses
 * @property-write string $password write-only password
 */
class User extends BaseActiveRecord implements IdentityInterface {
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_GUEST = 1;

    const SCENARIO_REGISTER_CONSOLE = 'register_console';
    
    const ADDRESSES_TEMPLATE = [
        ''
    ];
    
    /** @var \common\models\entities\UserAddresses[]  */
    protected $_addresses = [];

    /**
     * Sign up
     * @param string $username
     * @param string $email
     * @param string $password
     * @throws Yii\base\Exception
     * @return User
     */
    public static function signUp(string $username, string $email, string $password): User {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        return $user;
    }

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
            'id',
            'username',
            'login',
            'password_hash',
            'password_reset_token',
            'email',
            'auth_key',
            'authKey',
            'status',
            'created_at',
            'updated_at',
        ];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['login', 'password'], 'safe'],
            [['username', 'password_hash', 'email'], 'required'],
            
            [['first_name', 'last_name'], 'trim'],
            [['first_name', 'last_name'], 'string', 'min' => 2, 'max' => 255],

            ['phone_number', 'trim'],
            ['phone_number', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            ['phone_number', 'string', 'min' => 9],

            ['company', 'trim'],
            ['company', 'string', 'min' => 3, 'max' => 255],
            
            ['agree_to_news', 'boolean'],
            
            [['status', 'created_at', 'updated_at', 'id'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
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
            'password_hash' => 'Пароль',
            'agree_to_news' => 'Согласие на новостную рассылку',
        ];
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
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
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
    public function setPassword($password)
    {
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
     * @inheritdoc
     */
    public function __get($name) {
        // TODO Why!!!?????
        return parent::__get($name); // TODO: Change the autogenerated stub
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses() {
        return $this->hasMany(UserAddresses::class, ['user_id' => 'id']);
    }

    /**
     * Set addresses
     * @param array $addresses
     * TODO Move it to business logic
     */
    public function setAddresses(array $addresses) {
        foreach($addresses as $address) {
            $this->addAddress($address);
        }
    }

    /**
     * Add address
     * @param UserAddresses $address
     * TODO Move it to business logic
     */
    public function addAddress(UserAddresses $address) {
        if (is_array($address) && ArrayHelper::isAssociative($address)) {
            $_address = new UserAddresses();
            $_address->setAttributes($address);
            $address = $_address;
        }
        
        $this->_addresses[] = $address;
    }
}
