<?php

use yii\db\Migration;

/**
 * Class m180307_102429_calculate_the_quantity_of_products_in_each_brand
 */
class m180307_102429_calculate_the_quantity_of_products_in_each_brand extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_brand}}', 'product_quantity', $this->integer(11)->defaultValue(0));
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_brand}}', 'product_quantity');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180307_102429_calculate_the_quantity_of_products_in_each_brand cannot be reverted.\n";

        return false;
    }
    */
}
