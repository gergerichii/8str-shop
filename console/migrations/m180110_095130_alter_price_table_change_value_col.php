<?php

use yii\db\Migration;

/**
 * Class m180110_095130_alter_price_table_change_value_col
 */
class m180110_095130_alter_price_table_change_value_col extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%product_price}}', 'value', $this->money()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180110_095130_alter_price_table_change_value_col cannot be reverted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180110_095130_alter_price_table_change_value_col cannot be reverted.\n";

        return false;
    }
    */
}
