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
            'username' => 'admin',
            'email' => 'admin@8str.ru',
            'password' => 'Volga2015',
        ]);
        $user->generateAuthKey();
        
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'email' => 'admin@8str.ru',
            'status' => 10,
            'password_hash' => $user->password_hash,
            'auth_key' => $user->auth_key,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        User::findOne(1)->delete();
    }

}
