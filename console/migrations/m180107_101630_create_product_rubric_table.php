<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product2product_rubric`.
 */
class m180107_101630_create_product_rubric_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment =<<<EOT
'Таблица отношения многие ко многим для связи продуктов с рубрикатором

Зависит от таблиц: product и rubric'
EOT;


        $this->createTable('{{%product2product_rubric}}', [
            'product_id' => $this->integer(11)->notNull(),
            'rubric_id' => $this->integer(11)->notNull(),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->createIndex(
            'product_rubric_index',
            '{{%product2product_rubric}}',
            ['[[product_id]]', '[[rubric_id]]'],
            true
        );

        $this->createIndex(
            'rubric_product_index',
            '{{%product2product_rubric}}',
            ['[[rubric_id]]', '[[product_id]]'],
            true
        );

        $this->addForeignKey(
            'product_rubric_product_fk',
            '{{%product2product_rubric}}', '[[product_id]]',
            '{{%product}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'product_rubric_rubric_fk',
            '{{%product2product_rubric}}', '[[rubric_id]]',
            '{{%product_rubric}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {

        $this->dropForeignKey('product_rubric_product_fk', '{{%product2product_rubric}}');
        $this->dropForeignKey('product_rubric_rubric_fk', '{{%product2product_rubric}}');
        $this->dropTable('{{%product2product_rubric}}');
    }
}
