<?php

use yii\db\Migration;

/**
 * Class m180426_111206_add_column_model_to_product_table
 */
class m180426_111206_add_column_model_to_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', '[[model]]', $this->string(150));
        $this->addColumn('{{%product}}', '[[vendor_code]]', $this->string(150));
        $this->addColumn('{{%product}}', '[[barcode]]', $this->string(150));
        $this->addColumn('{{%product}}', '[[warranty]]', $this->string(30));
        $this->addColumn('{{%product}}', '[[delivery_days]]', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', '[[model]]');
        $this->dropColumn('{{%product}}', '[[vendor_code]]');
        $this->dropColumn('{{%product}}', '[[barcode]]');
        $this->dropColumn('{{%product}}', '[[warranty]]');
        $this->dropColumn('{{%product}}', '[[delivery_days]]');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180426_111206_add_column_model_to_product_table cannot be reverted.\n";

        return false;
    }
    */
}
