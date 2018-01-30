<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 04.01.2018
 * Time: 16:07
 */

namespace common\services;

use Yii;
use common\models\entities\User;
use common\models\forms\SignupForm;

/**
 * Class UserService
 * @package common\services
 */
class UserService {
    /**
     * @param SignupForm $form
     * @return User
     * @throws Yii\base\Exception
     */
    public function signUp (SignupForm $form) : User {
        if (User::find()->andWhere(['username' => $form->username])->one()) {
            throw new \DomainException('Username already exists');
        }
        if (User::find()->andWhere(['email' => $form->email])->one()) {
            throw new \DomainException('Email already exists');
        }

        $user = User::signUp($form->username, $form->email, $form->password);

        if (!$user->save()) {
            throw new \RuntimeException('User saving error.');
        }
        return  $user;
    }
}