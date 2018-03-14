<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 04.01.2018
 * Time: 16:07
 */

namespace common\services;

use Yii;
use common\models\forms\SignupForm;

/**
 * Class UserService
 * @package common\services
 */
class UserService {
    /**
     * @param SignupForm $form
     *
     * @return bool
     * @throws \Yii\base\Exception
     */
    public static function signUp (SignupForm $form) : bool {
        $form->user->setPassword();
        $form->user->generateAuthKey();
        try {
            Yii::$app->db->transaction(function() use ($form) {
                if(!$form->user->save()) {
                    throw new \Exception('Ошибка записи пользователя');
                }
                $addresses = (is_array($form->userAddresses)) ? $form->userAddresses : [$form->userAddresses];
                foreach($addresses as $address) {
                    $form->user->link('addresses', $address);
                }
            });
        } catch(\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        } catch(\Throwable $e) {
            throw new \RuntimeException($e->getMessage());
        }
    
        return  true;
    }
}