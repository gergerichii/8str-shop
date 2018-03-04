<?php

use common\models\entities\User;
use yii\db\Migration;

/**
 * Class m130524_201443_create_admin_user
 */
class m130524_201443_create_admin_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $user = new User([
            'scenario' => User::SCENARIO_REGISTER_CONSOLE,
            'username' => 'admin',
            'email' => 'admin@8str.ru',
            'password' => 'Volga2015',
        ]);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        return $user->save();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        User::findOne(1)->delete();
    }

}
