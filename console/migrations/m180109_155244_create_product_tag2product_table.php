<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_tag2product`.
 */
class m180109_155244_create_product_tag2product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableComment = '\'Таблица сцепки "Многие ко многим" для меток и продуктов\'';

        $this->createTable('{{%product_tag2product}}', [
            'product_tag_id' => $this->integer(11)->notNull(),
            'product_id' => $this->integer(11)->notNull(),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->addForeignKey('product_tag2product_tag_fk', '{{%product_tag2product}}', 'product_tag_id',
            '{{%product_tag}}', 'id', 'CASCADE');
        $this->addForeignKey('product_tag2product_product_fk', '{{%product_tag2product}}', 'product_id',
            '{{%product}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('product_tag2product_tag_fk', '{{%product_tag2product}}');
        $this->dropForeignKey('product_tag2product_product_fk', '{{%product_tag2product}}');

        $this->dropTable('{{%product_tag2product}}');
    }
}
