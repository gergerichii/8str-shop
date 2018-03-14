<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 14.02.2018
 * Time: 17:37
 */

namespace common\modules\order\forms\frontend;
use common\models\forms\LoginForm;
use yii\helpers\ArrayHelper;

class Step1Form extends LoginForm {
    public $orderMode = Step2Form::SCENARIO_REGISTER;
    
    public function rules() {
        return ArrayHelper::merge(parent::rules(), [
            ['orderMode', 'in', 'range' => ['guest', 'login', 'register'], 'strict' => false]
        ]);
    }
    
    public function attributeLabels() {
        return [
            'orderMode' => 'Способ идентификации покупателя',
            'rememberMe' => 'Запомнить пароль',
            'username' => 'Email/Логин',
            'password' => 'Пароль',
        ];
    }
    
    public function scenarios() {
        return [
            'default' => [
                'orderMode', 'username', 'password', 'rememberMe'
            ],
            'login' => [
                'orderMode', 'username', 'password', 'rememberMe'
            ]
        ];
    }
}