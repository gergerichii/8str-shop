<?php

use yii\db\Migration;

/**
 * Class m180310_133745_add_permission_for_admin_panel
 */
class m180310_133745_add_permission_for_admin_panel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        $canAccessToAdminPanel = $auth->createPermission('access_to_admin_panel');
        $auth->add($canAccessToAdminPanel);
        $auth->addChild($auth->getRole('admin'), $canAccessToAdminPanel);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeChild($auth->getRole('admin'), $auth->getPermission('access_to_admin_panel'));
        $auth->remove($auth->getPermission('access_to_admin_panel'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180310_133745_add_permission_for_admin_panel cannot be reverted.\n";

        return false;
    }
    */
}
