<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 04.01.2018
 * Time: 16:07
 */

namespace common\services;

use common\models\entities\User;
use Yii;
use common\models\forms\SignupForm;
use yii\base\Exception;

/**
 * Class UserService
 * @package common\services
 */
class UserService {
    /**
     * @param SignupForm $form
     *
     * @param bool       $validate
     *
     * @return bool
     * @throws \Yii\base\Exception
     */
    public static function signUp (SignupForm $form, $validate = true) {
        /** @var \common\models\entities\User $user */
        $user = $form->user;
        /** @var \common\models\entities\User $existsUser */
        $existsUser = null;
        if ($user->scenario === User::SCENARIO_REGISTER_GUEST) {
            if (!$existsUser = User::findByEmail($user->email, User::STATUS_GUEST)) {
                $user->status = User::STATUS_GUEST;
                $user->username = 'Guest_' . Yii::$app->security->generateRandomString();
            }
        }
        if ($existsUser) {
            foreach ($user->getAttributes(null, ['status']) as $attributeName => $attribute) {
                if ($attribute !== null) {
                    $existsUser->setAttribute($attributeName, $attribute);
                }
            }
            $existsUser->scenario = $user->scenario;
            $user = $existsUser;
        } else {
            $user->setPassword();
            $user->generateAuthKey();
        }
        try {
            Yii::$app->db->transaction(function() use ($form, $validate, $user) {
                if(!$user->save($validate)) {
                    throw new \Exception('Ошибка записи пользователя');
                }
                /** @var \common\models\entities\UserAddresses[] $addresses */
                $addresses = (is_array($form->userAddresses)) ? $form->userAddresses : [$form->userAddresses];
                foreach($addresses as $address) {
                    if ($validate && !$address->validate()) {
                        return false;
                    }
                    $user->link('addresses', $address);
                }
            });
        } catch(\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        } catch(\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }
    
        return Yii::$app->getUser()->login($user);
    }
}