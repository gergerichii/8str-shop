<?php

use yii\db\Migration;

/**
 * Class m180301_105134_add_the_table_of_news
 */
class m180301_105134_add_the_table_of_news extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp() {
        $tableComment = "'Aricles'";
        $this->createTable('{{%article}}', [
            '[[id]]' => $this->primaryKey(11),
            '[[title]]' => $this->string(255)->notNull(),
            '[[alias]]' => $this->string(255)->notNull(),
            '[[introtext]]' => $this->text()->notNull(),
            '[[fulltext]]' => $this->text(),
            '[[created_at]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            '[[modified_at]]' => $this->timestamp()->null()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP'),
            '[[published_at]]' => $this->timestamp()->notNull()->defaultExpression('NOW()'),
            '[[creator_id]]' => $this->integer(11)->notNull(),
            '[[modifier_id]]' => $this->integer(11)->notNull(),
            '[[image]]' => $this->string(512)->null(),
            '[[external_id]]' => $this->integer(11)->null()
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");

        $this->addForeignKey(
            'fk_article_creator_id',
            '{{%article}}',
            '[[creator_id]]',
            '{{%user}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_article_modifier_id',
            '{{%article}}',
            '[[modifier_id]]',
            '{{%user}}',
            '[[id]]',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropForeignKey('fk_article_creator_id', '{{%article}}');
        $this->dropForeignKey('fk_article_modifier_id', '{{%article}}');
        $this->dropTable('{{article}}');
    }
}
