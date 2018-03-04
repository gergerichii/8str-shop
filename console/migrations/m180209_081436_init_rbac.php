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
        $user = new User(['scenario' => User::SCENARIO_REGISTER_CONSOLE]);
        $user->setAttributes([
            'id' => 2,
            'username' => 'guest',
            'email' => 'guest@guest.guest',
            'status' => User::STATUS_ACTIVE
        ]);
        $user->setPassword('7777777');
        $user->generateAuthKey();
        if ($user->save()) {
            $auth->assign($guest, $user->id);
        } else {
            return false;
        }

        // Admin can see admin settings in the rubrics tree
        $canSeeAdminSettingsInRubrics = $auth->createPermission('see_admin_settings_in_rubrics');
        $auth->add($canSeeAdminSettingsInRubrics);
        $auth->addChild($admin, $canSeeAdminSettingsInRubrics);

        return true;
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

        return true;
    }

}
