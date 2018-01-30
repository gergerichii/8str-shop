<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_price`.
 */
class m180107_132043_create_product_price_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment =<<<EOT
'Таблица истории цен по доменам и типам цен.'
EOT;
        
        $this->createTable('{{%product_price}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[product_price_type_id]]' => $this->integer(11)->notNull()
                ->defaultValue(1),
            '[[product_id]]' => $this->integer(11)->notNull(),
            /** Имя домена */
            '[[domain_name]]' => $this->string(150)->notNull(),
            '[[author_id]]' => $this->integer(11)->notNull(),
            '[[active_from]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->createIndex('price_type_product_index', '{{%product_price}}',
            ['[[domain_name]]', '[[product_id]]', '[[active_from]]']
        );

        $this->createIndex('active_from_index', '{{%product_price}}',
            [
                '[[domain_name]]',
                '[[product_price_type_id]]',
                '[[product_id]]',
                '[[active_from]]'
            ],
            true
        );

        $this->addForeignKey(
            'product_price_product_price_type_fk',
            '{{%product_price}}',
            '[[product_price_type_id]]',
            '{{%product_price_type}}',
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'product_price_product_fk',
            '{{%product_price}}',
            '[[product_id]]',
            '{{%product}}',
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'author_id_user_fk',
            '{{%product_price}}',
            '[[author_id]]',
            '{{%user}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropForeignKey('product_price_product_price_type_fk', '{{%product_price}}');
        $this->dropForeignKey('product_price_product_fk', '{{%product_price}}');
        $this->dropForeignKey('author_id_user_fk', '{{%product_price}}');
        $this->dropTable('{{%product_price}}');
    }
}
