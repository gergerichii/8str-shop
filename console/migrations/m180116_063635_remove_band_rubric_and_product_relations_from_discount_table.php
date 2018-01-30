<?php

use yii\db\Migration;

/**
 * Class m180116_063635_remove_band_rubric_and_product_relations_from_discount_table
 */
class m180116_063635_remove_band_rubric_and_product_relations_from_discount_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_price_discount_product_id_product_fk', 'product_price_discount');
        $this->dropForeignKey('product_price_discount_product_brand_id_product_tag_fk', 'product_price_discount');
        $this->dropForeignKey('product_price_discount_product_rubric_id_product_tag_fk', 'product_price_discount');

        $this->dropColumn('product_price_discount', 'product_id');
        $this->dropColumn('product_price_discount', 'product_brand_id');
        $this->dropColumn('product_price_discount', 'product_rubric_id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->addColumn('product_price_discount', 'product_id', $this->integer(11)->null());
        $this->addColumn('product_price_discount', 'product_brand_id', $this->integer(11)->null());
        $this->addColumn('product_price_discount', 'product_rubric_id', $this->integer(11)->null());

        $this->addForeignKey(
            'product_price_discount_product_id_product_fk',
            '{{%product_price_discount}}',
            '[[product_id]]',
            '{{%product}}',
            '[[id]]',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'product_price_discount_product_brand_id_product_tag_fk',
            '{{%product_price_discount}}',
            '[[product_brand_id]]',
            '{{%product_brand}}',
            '[[id]]',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'product_price_discount_product_rubric_id_product_tag_fk',
            '{{%product_price_discount}}',
            '[[product_rubric_id]]',
            '{{%product_rubric}}',
            '[[id]]',
            'SET NULL',
            'CASCADE'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180116_063635_remove_band_rubric_and_product_relations_from_discount_table cannot be reverted.\n";

        return false;
    }
    */
}
