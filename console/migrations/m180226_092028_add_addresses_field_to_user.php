<?php

use yii\db\Migration;

/**
 * Class m180226_092028_add_addresses_field_to_user
 */
class m180226_092028_add_addresses_field_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', '[[addresses]]', 'JSON NOT NULL CHECK(JSON_VALID([[1c_data]]))');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', '[[addresses]]');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180226_092028_add_addresses_field_to_user cannot be reverted.\n";

        return false;
    }
    */
}
