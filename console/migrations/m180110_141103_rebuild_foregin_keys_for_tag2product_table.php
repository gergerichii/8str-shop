<?php

use yii\db\Migration;

/**
 * Class m180110_141103_rebuild_foregin_keys_for_tag2product_table
 */
class m180110_141103_rebuild_foregin_keys_for_tag2product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_tag2product_tag_fk', '{{%product_tag2product}}');
        $this->dropForeignKey('product_tag2product_product_fk', '{{%product_tag2product}}');

        $this->addForeignKey('product_tag2product_tag_fk', '{{%product_tag2product}}', 'product_tag_id',
            '{{%product_tag}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('product_tag2product_product_fk', '{{%product_tag2product}}', 'product_id',
            '{{%product}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180110_141103_rebuild_foregin_keys_for_tag2product_table cannot be reverted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180110_141103_rebuild_foregin_keys_for_tag2product_table cannot be reverted.\n";

        return false;
    }
    */
}
