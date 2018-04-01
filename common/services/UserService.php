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
     *
     * @param bool       $validate
     *
     * @return bool
     * @throws \Yii\base\Exception
     */
    public static function signUp (SignupForm &$form, $validate = true) {
        /** @var \common\models\entities\User $user */
        $user = $form->user;
        /** @var \common\models\entities\User $existsUser */
        $existsUser = $existsUser = User::findByEmail($user->email, User::STATUS_GUEST);
        if ($user->scenario === User::SCENARIO_REGISTER_GUEST) {
            if (!$existsUser) {
                $user->status = User::STATUS_GUEST;
                $user->username = 'Guest_' . Yii::$app->security->generateRandomString();
            }
        } elseif($existsUser) {
            $existsUser->status = User::STATUS_ACTIVE;
        }
        if ($existsUser) {
            foreach ($user->getAttributes(null, ['status']) as $attributeName => $attribute) {
                if ($attribute !== null) {
                    $existsUser->setAttribute($attributeName, $attribute);
                }
            }
            $existsUser->scenario = $user->scenario;
            $existsUser->password = $user->password;
            $existsUser->password_confirm = $user->password_confirm;
            $user = $existsUser;
            $form->user = $user;
        }
        $user->setPassword();
        $user->generateAuthKey();
        
        try {
            Yii::$app->db->transaction(function() use ($form, $validate, $user) {
                if(!$user->save($validate)) {
                    throw new \Exception('Ошибка записи пользователя');
                }
                if ($user->scenario !== User::SCENARIO_REGISTER_GUEST) {
                    /** @var \common\models\entities\UserAddresses[] $addresses */
                    $addresses = (is_array($form->userAddresses)) ? $form->userAddresses : [$form->userAddresses];
                    foreach($addresses as $address) {
                        if ($address->isNewRecord && empty($address->oldAttributes)) {
                            continue;
                        }
                        if ($validate && !$address->validate()) {
                            return false;
                        }
                        $user->link('userAddresses', $address);
                    }
                    $form->userAddresses = $user->userAddresses;
                }
                return true;
            });
        } catch(\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        } catch(\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }
        
        return Yii::$app->getUser()->login($user);
    }
}