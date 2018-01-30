<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product_price_type`.
 */
class m180107_124952_create_product_price_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment =<<<EOT
'Таблица типа цены для продукта. Используется для заполнения цен в JSON поле продукта и для скидок

Зависящая таблица discount'
EOT;

        $this->createTable('{{%product_price_type}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[name]]' => $this->string(155)->notNull()->unique(),
            '[[title]]' => $this->string(255),
            '[[desc]]' => $this->string(255),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->insert('{{%product_price_type}}', [
            '[[name]]' => 'Розница',
            '[[title]]' => 'цена',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%product_price_type}}');
    }
}
