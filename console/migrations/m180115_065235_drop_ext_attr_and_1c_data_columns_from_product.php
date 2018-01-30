<?php

use yii\db\Migration;

/**
 * Class m180115_065235_drop_ext_attr_and_1c_data_columns_from_product
 */
class m180115_065235_drop_ext_attr_and_1c_data_columns_from_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->dropColumn('product', '1c_data');
        $this->dropColumn('product', 'ext_attributes');
        $this->dropColumn('product', 'is_new');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->addColumn('product', '1c_data', 'JSON NOT NULL CHECK(JSON_VALID([[1c_data]]))');
        $this->addColumn('product', 'ext_attributes', 'JSON NOT NULL CHECK(JSON_VALID([[ext_attributes]]))');
        $this->addColumn('product','is_new', $this->boolean()->notNull()->defaultValue(FALSE));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180115_065235_drop_ext_attr_and_1c_data_columns_from_product cannot be reverted.\n";

        return false;
    }
    */
}
