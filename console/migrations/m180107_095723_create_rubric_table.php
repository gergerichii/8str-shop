<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rubric`.
 */
class m180107_095723_create_rubric_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up(){
        $tableComment =<<<EOT
'Рубрикатор магазина. Дерево построено по принципу NESTED SETS (вложенные множества).

Зависяща таблица: таблица отношения многие ко многим product_rubric'
EOT;


        $this->createTable('{{%product_rubric}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[tree]]' => $this->integer(11)->notNull()->unsigned(),
            '[[level]]' => $this->integer(11)->notNull()->unsigned(),
            '[[left_key]]' => $this->integer(11)->notNull()->unsigned(),
            '[[right_key]]' => $this->integer(11)->notNull()->unsigned(),
            '[[name]]' => $this->string(150)->notNull(),
            '[[title]]' => $this->string(255),
            '[[desc]]' => $this->text(),
            '[[visible_on_home_page]]' => $this->boolean()->notNull()->unsigned()->defaultValue(1)
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->createIndex(
            'rubric_left_key_right_key_level_unique',
            '{{%product_rubric}}',
            [
                '[[tree]]', '[[left_key]]', '[[right_key]]', '[[level]]'
            ],
            true
        );

        $this->createIndex(
            'tree_level_name_index',
            '{{%product_rubric}}',
            ['[[tree]]', '[[level]]', '[[name]]']
        );

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%product_rubric}}');
    }
}
