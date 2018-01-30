<?php

use yii\db\Migration;

/**
 * Class m180116_124959_add_fields_in_tag_table
 */
class m180116_124959_add_fields_in_tag_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_tag', 'show_on_product',
            $this->integer(11)->notNull()->defaultValue(0)
        );
        $this->addColumn('product_tag', 'use_as_group',
            $this->integer(11)->notNull()->defaultValue(0)
        );

        $this->update('product_tag', [
            'use_as_group' => 1,
            'show_on_product' => 1,
        ], ['name' => 'new']);
        $this->update('product_tag', [
            'use_as_group' => 2,
            'show_on_product' => 2,
        ], ['name' => 'bestseller']);
        $this->update('product_tag', [
            'use_as_group' => 3,
            'show_on_product' => 3,
        ], ['name' => 'promo']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_tag', 'show_on_product');
        $this->dropColumn('product_tag', 'use_as_group');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180116_124959_add_fields_in_tag_table cannot be reverted.\n";

        return false;
    }
    */
}
