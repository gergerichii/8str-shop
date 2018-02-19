<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 14.02.2018
 * Time: 17:39
 */

namespace common\modules\order\forms\frontend;
use yii\base\Model;

class Step2Form extends Model {
    public const SCENARIO_REGISTER = 'register';
    public const SCENARIO_GUEST = 'guest';
    
    public $firstName;
    public $lastName;
    public $login;
    public $email;
    public $phoneNumber;
    public $company;
    public $password;
    public $passwordConfirm;
    public $agreeToNews = true;
    public $address;
    public $city;
    public $region;
    public $privacyAgree = false;
    
    
    public function rules() {
        return [
            [['firstName', 'lastName', 'login'], 'trim'],
            [['firstName', 'lastName', 'login'], 'required'],
            [['firstName', 'lastName', 'login'], 'string', 'min' => 2, 'max' => 255],
            
            [['password', 'passwordConfirm'], 'trim'],
            [['password', 'passwordConfirm'], 'string', 'min' => 6, 'max' => 255],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password'],
            ['password', 'compare'],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            
            ['phoneNumber', 'trim'],
            ['phoneNumber', 'required'],
            ['phoneNumber', 'match', '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            ['phoneNumber', 'string', 'min' => 9],

            [['company', 'city', 'region', 'address'], 'trim'],
            [['company', 'city', 'region', 'address'], 'required'],
            [['company', 'city', 'region', 'address'], 'match', '/^[\w+]$/'],
            [['company', 'city', 'region'], 'string', 'min' => 3, 'max' => 255],
            ['address', 'string', 'min' => 10, 'max' => 255],

            ['region', 'trim'],
            ['region', 'string', 'max' => 255, 'skipOnEmpty' => true],

            [['agreeToNews', 'privacyAgree'], 'boolean'],
            ['privacyAgree', 'required'],
            ['privacyAgree', 'compare', 'compareValue' => true, 'operator' => '='],
            ['agreeToNews', 'default', false],
        ];
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_GUEST] = [
            'firstName', 'lastName', 'email', 'phoneNumber',
            'agreeToNews', 'address', 'city', 'region', 'privacyAgree'
        ];
        $scenarios[self::SCENARIO_REGISTER] = [
            'firstName', 'lastName', 'login', 'email', 'phoneNumber', 'company', 'password', 'passwordConfirm',
            'agreeToNews', 'address', 'city', 'region', 'privacyAgree'
        ];
        return $scenarios;
    }
}