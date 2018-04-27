<?php

use yii\db\Migration;

/**
 * Class m180427_094833_add_country_id_to_brand_table
 */
class m180427_094833_add_country_id_to_brand_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_brand}}', '[[country_id]]', $this->integer(11));
        $this->addForeignKey(
            'product_brand_country_id_country_id_fk',
            '{{%product_brand}}',
            '[[country_id]]',
            '{{%country}}',
            '[[id]]'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_brand_country_id_country_id_fk', '{{%product_brand}}');
        $this->dropColumn('{{%product_brand}}', '[[country_id]]');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180427_094833_add_country_id_to_brand_table cannot be reverted.\n";

        return false;
    }
    */
}
