<?php

use yii\db\Migration;

/**
 * Class m180116_073447_add_value_and_weight_to_discount_table
 */
class m180116_073447_add_value_and_weight_to_discount_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_price_discount', 'value',
            $this->decimal(2)->notNull()->defaultValue(0)
        );
        $this->addColumn('product_price_discount', 'weight',
            $this->integer(11)->notNull()->defaultValue(50)
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_price_discount', 'value');
        $this->dropColumn('product_price_discount', 'weight');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180116_073447_add_value_and_weight_to_discount_table cannot be reverted.\n";

        return false;
    }
    */
}
