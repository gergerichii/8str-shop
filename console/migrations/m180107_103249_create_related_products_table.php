<?php

use yii\db\Migration;

/**
 * Handles the creation of table `related_products`.
 */
class m180107_103249_create_related_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment =<<<EOT
'Таблица зависимости многие ко многим для товаров и сопуствующих товаров

Зависит от таблицы product'
EOT;


        $this->createTable('{{%related_product2product}}', [
            '[[parent_product_id]]' => $this->integer(11)->notNull(),
            '[[related_product_id]]' => $this->integer(11)->notNull(),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->createIndex(
            'parent_related_index',
            '{{%related_product2product}}',
            ['[[parent_product_id]]', '[[related_product_id]]'],
            true
        );

        $this->createIndex(
            'related_parent_index',
            '{{%related_product2product}}',
            ['[[related_product_id]]', '[[parent_product_id]]'],
            true
        );

        $this->addForeignKey(
            'parent_product_product_fk',
            '{{%related_product2product}}', '[[parent_product_id]]',
            '{{%product}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'related_product_product_fk',
            '{{%related_product2product}}', '[[related_product_id]]',
            '{{%product}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropForeignKey('parent_product_product_fk', '{{%related_product2product}}');
        $this->dropForeignKey('related_product_product_fk', '{{%related_product2product}}');
        $this->dropTable('{{%related_product2product}}');
    }
}
