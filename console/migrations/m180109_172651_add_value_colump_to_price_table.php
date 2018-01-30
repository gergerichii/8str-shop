<?php

use yii\db\Migration;

/**
 * Class m180109_172651_add_value_colump_to_price_table
 */
class m180109_172651_add_value_colump_to_price_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_price}}', 'value', $this->money(2)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_price}}', 'value');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180109_172651_add_value_colump_to_price_table cannot be reverted.\n";

        return false;
    }
    */
}
