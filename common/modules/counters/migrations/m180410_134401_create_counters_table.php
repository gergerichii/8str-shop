<?php

use yii\db\Migration;
use yii\web\View;

/**
 * Handles the creation of table `counters`.
 */
class m180410_134401_create_counters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableComment =<<<EOT
'Таблица для js счетчиков и скриптов'
EOT;
        $this->createTable('counters', [
            '[[id]]' => $this->primaryKey(11),
            '[[name]]' => $this->string(255)->unique(),
            '[[value]]' => $this->text()->notNull(),
            '[[position]]' => $this->integer(3)->defaultValue(View::POS_BEGIN),
            '[[included_pages]]' => $this->text()->null(),
            '[[excluded_pages]]' => $this->text()->null(),
            '[[active]]' => $this->boolean()->defaultValue(true),
            '[[created_by]]' => $this->integer(11)->notNull(),
            '[[modified_by]]' => $this->integer(11)->notNull(),
            '[[created_at]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            '[[modified_at]]' => $this->timestamp()->null()
                ->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");
        
        $this->addForeignKey(
            'counters_created_by_user_id_fk',
            '{{%counters}}', '[[created_by]]',
            '{{%user}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'counters_modified_by_user_id_fk',
            '{{%counters}}', '[[modified_by]]',
            '{{%user}}', '[[id]]',
            'CASCADE',
            'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('counters_created_by_user_id_fk', '{{%counters}}');
        $this->dropForeignKey('counters_modified_by_user_id_fk', '{{%counters}}');
        $this->dropTable('{{%counters}}');
    }
}
