<?php

use yii\db\Migration;

/**
 * Class m180221_073950_add_ext_firlds_to_user
 */
class m180221_073950_add_ext_firlds_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'first_name', $this->string(255)->null());
        $this->addColumn('{{%user}}', 'last_name', $this->string(255)->null());
        $this->addColumn('{{%user}}', 'phone_number', $this->string(20)->null());
        $this->addColumn('{{%user}}', 'company', $this->string(255)->null());
        $this->addColumn('{{%user}}', 'agree_to_news', $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'first_name');
        $this->dropColumn('{{%user}}', 'last_name');
        $this->dropColumn('{{%user}}', 'phone_number');
        $this->dropColumn('{{%user}}', 'company');
        $this->dropColumn('{{%user}}', 'agree_to_news');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180221_073950_add_ext_firlds_to_user cannot be reverted.\n";

        return false;
    }
    */
}
