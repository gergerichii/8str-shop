<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_discount`.
 */
class m180107_135342_create_product_discount_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment =<<<EOT
'Таблица скидок. Содержит историю скидок.

Зависит от user, product_tag, product_id, product_brand_id, product_rubric_id'
EOT;

        $this->createTable('{{%product_price_discount}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[name]]' => $this->string(155)->notNull()->unique(),
            '[[title]]' => $this->string(155),
            '[[created_at]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            '[[author_id]]' => $this->integer(11)->notNull(),
            '[[active_from]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            '[[active_to]]' => $this->timestamp()->null(),
            '[[status]]' => $this->integer(2)->notNull()
                ->defaultValue(0),
            '[[product_tag_id]]' => $this->integer(11)->null(),
            '[[product_id]]' => $this->integer(11)->null(),
            '[[product_brand_id]]' =>  $this->integer(11)->null(),
            '[[product_rubric_id]]' =>  $this->integer(11)->null(),

        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->createIndex(
            'product_id_active_from_active_active_to_status_index',
            '{{%product_price_discount}}',
            ['[[product_id]]', '[[active_from]]', '[[active_to]]', '[[status]]']
        );
        $this->createIndex(
            'product_tag_id_active_from_active_active_to_status_index',
            '{{%product_price_discount}}',
            ['[[product_tag_id]]', '[[active_from]]', '[[active_to]]', '[[status]]']
        );
        $this->createIndex(
            'product_brand_id_active_from_active_active_to_status_index',
            '{{%product_price_discount}}',
            ['[[product_brand_id]]', '[[active_from]]', '[[active_to]]', '[[status]]']
        );
        $this->createIndex(
            'product_rubric_id_active_from_active_active_to_status_index',
            '{{%product_price_discount}}',
            ['[[product_rubric_id]]', '[[active_from]]', '[[active_to]]', '[[status]]']
        );

        $this->addForeignKey(
            'product_price_discount_author_id_user_fk',
            '{{%product_price_discount}}',
            '[[author_id]]',
            '{{%user}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );
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
            'product_price_discount_product_tag_id_product_tag_fk',
            '{{%product_price_discount}}',
            '[[product_tag_id]]',
            '{{%product_tag}}',
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

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropForeignKey('product_price_discount_author_id_user_fk', '{{%product_price_discount}}');
        $this->dropForeignKey('product_price_discount_product_id_product_fk', '{{%product_price_discount}}');
        $this->dropForeignKey('product_price_discount_product_tag_id_product_tag_fk', '{{%product_price_discount}}');
        $this->dropForeignKey('product_price_discount_product_brand_id_product_tag_fk', '{{%product_price_discount}}');
        $this->dropForeignKey('product_price_discount_product_rubric_id_product_tag_fk', '{{%product_price_discount}}');
        $this->dropTable('{{%product_price_discount}}');
    }
}
