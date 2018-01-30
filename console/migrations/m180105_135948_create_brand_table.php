<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 *
 * Таблица брэндов для каталога
 */
class m180105_135948_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableComment = "'Таблица брэндов для каталога. \n\r Зависящая таблица product'";
        $this->createTable('{{%product_brand}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[name]]' => $this->string(150)->unique()->notNull(),
            '[[desc]]' => $this->text(),
            '[[logo]]' => $this->string(150),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product_brand}}');
    }
}
