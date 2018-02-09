<?php

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
        $user = new \common\models\entities\User();
        $user->setAttributes([
            'id' => 2,
            'username' => 'guest',
            'email' => 'guest@guest.guest',
            'status' => \common\models\entities\User::STATUS_ACTIVE
        ]);
        $user->setPassword('7777777');
        $user->generateAuthKey();
        if ($user->save()) {
            $auth->assign($guest, $user->id);
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        // Remove all rbac
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Delete guest
        $user = \common\models\entities\User::findOne(2);
        if (!$user) {
            return true;
        }

        if (false === $user->delete()) {
            return false;
        }
    }

}
