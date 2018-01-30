<?php

use yii\db\Migration;

/**
 * Class m180115_151024_remove_price_type_column_from_price_table
 */
class m180115_151024_remove_price_type_column_from_price_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_price_product_price_type_fk', 'product_price');
        $this->dropColumn('product_price', 'product_price_type_id');
        $this->dropTable('product_price_type');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
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

        $this->addColumn('product_price', 'product_price_type_id', $this->integer(11)->notNull()
            ->defaultValue(1));

        $this->addForeignKey(
            'product_price_product_price_type_fk',
            '{{%product_price}}',
            '[[product_price_type_id]]',
            '{{%product_price_type}}',
            '[[id]]',
            'CASCADE',
            'CASCADE'
        );

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180115_151024_remove_price_type_column_from_price_table cannot be reverted.\n";

        return false;
    }
    */
}
