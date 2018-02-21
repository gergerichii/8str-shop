<?php

use yii\db\Migration;

/**
 * Class m180221_091910_create_table_user_addresses
 */
class m180221_091910_create_table_user_addresses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = '{{%user_addresses}}';
        
        $this->createTable($table, [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer(11)->notNull(),
            'region' => $this->string(255)->null(),
            'city' => $this->string(255)->notNull(),
            'address' => $this->string(255)->notNull(),
        ]);
        
        $this->createIndex('user_address_region_index', $table, ['region', 'city']);
        $this->addForeignKey('user_address_user_fk', $table, 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = '{{%user_addresses}}';
        $this->dropForeignKey('user_address_user_fk', $table);
        $this->dropTable($table);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180221_091910_create_table_user_addresses cannot be reverted.\n";

        return false;
    }
    */
}
