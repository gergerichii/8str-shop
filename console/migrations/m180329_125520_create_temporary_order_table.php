<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tmporary_order`.
 */
class m180329_125520_create_temporary_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableComment =<<<EOT
'Временная таблица для оформленных заказов для быстрой разработки'
EOT;
        $this->createTable('temporary_order', [
            '[[id]]' => $this->primaryKey(),
            '[[user_id]]' => $this->integer(11),
            '[[user_address_id]]' => $this->integer(11),
            '[[delivery_options]]' => 'JSON NOT NULL CHECK(JSON_VALID([[delivery_options]]))',
            '[[payment_method]]' => 'JSON NOT NULL CHECK(JSON_VALID([[payment_method]]))',
            '[[order_data]]' => 'JSON NOT NULL CHECK(JSON_VALID([[payment_method]]))',
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");
        
        $this->addForeignKey(
            'temporary_order_user_id_fk',
            '{{%temporary_order}}', '[[user_id]]',
            '{{%user}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'temporary_order_user_address_id_fk',
            '{{%temporary_order}}', '[[user_address_id]]',
            '{{%user_addresses}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('temporary_order_user_id_fk', '{{%temporary_order}}');
        $this->dropForeignKey('temporary_order_user_address_id_fk', '{{%temporary_order}}');
        $this->dropTable('tmporary_order');
    }
}
