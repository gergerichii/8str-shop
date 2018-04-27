<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country`.
 */
class m180427_094542_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableComment =<<<EOT
'Таблица стран'
EOT;
        $this->createTable('{{%country}}', [
            '[[id]]' => $this->primaryKey(),
            '[[name]]' => $this->string(150),
        ], 'ENGINE=InnoDB, COMMENT=' . $tableComment . ", COLLATE 'utf8_general_ci'");
        
        $this->insert('{{%country}}', ['name' => 'Россия']);
        $this->insert('{{%country}}', ['name' => 'Китай']);
        $this->insert('{{%country}}', ['name' => 'Корея']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%country}}');
    }
}
