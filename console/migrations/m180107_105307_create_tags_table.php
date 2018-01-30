<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tags`.
 */
class m180107_105307_create_tags_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up() {
        $tableComment =<<<EOT
'Метки используются для служебных нужд и для вывода пользователю дополнительной информации
о товаре. На пример, что товар со скидкой, или что этот товар в враспродаже.'
EOT;

        $this->createTable('{{%product_tag}}', [
            'id' => $this->primaryKey(11),
            'name' => $this->string(150)->notNull()->unique(),
            'title' => $this->string(150),
            'desc' => $this->text(),
            'status' => $this->integer(2)->notNull()
                ->defaultValue(1),
            /** Дополнительные настройки для метки */
            'add_data' => 'JSON NOT NULL CHECK(JSON_VALID([[add_data]]))',
            /** Виртуальные столбцы. Их можно добавлять сколько угодно и можно индексировать */
            'show_at' => $this->dateTime()->append("AS ('$.showAt')"),
            'hide_at' => $this->dateTime()->append("AS ('$.hideAt')"),
            'image' => $this->string(255)->append("AS ('$.image')"),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropTable('{{%product_tag}}');
    }
}
