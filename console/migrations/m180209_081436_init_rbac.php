<?php

use common\models\entities\User;
use yii\db\Migration;

/**
 * Class m180209_081436_init_rbac
 *
 * Adding rolls, admin and guest, and the guest user.
 */
class m180209_081436_init_rbac extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $auth = Yii::$app->authManager;

        // Add 'admin' role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->assign($admin, 1);

        // Add 'guest' role
        $guest = $auth->createRole('guest');
        $auth->add($guest);

        // Add guest user
        $user = new User();
        $user->setAttributes([
            'id' => 2,
            'username' => 'guest',
            'email' => 'guest@guest.guest',
            'status' => 10
        ]);
        $user->setPassword('7777777');
        $user->generateAuthKey();
        
        try{
            $this->insert('{{%user}}', [
                'id' => 2,
                'username' => 'guest',
                'email' => 'guest@guest.guest',
                'status' => 10,
                'password_hash' => $user->password_hash,
                'auth_key' => $user->auth_key,
                'created_at' => time(),
                'updated_at' => time(),
            ]);
            $auth->assign($guest, $user->id);
        } catch(Exception $e) {
            echo $e->getMessage();
            return false;
        }
        
        // Admin can see admin settings in the rubrics tree
        $canSeeAdminSettingsInRubrics = $auth->createPermission('see_admin_settings_in_rubrics');
        $auth->add($canSeeAdminSettingsInRubrics);
        $auth->addChild($admin, $canSeeAdminSettingsInRubrics);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        // Remove all rbac
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Delete guest
        $user = User::findOne(2);
        if (!$user) {
            return true;
        }

        if (false === $user->delete()) {
            return false;
        }
    }

}
