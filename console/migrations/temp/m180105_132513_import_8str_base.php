<?php

use yii\db\Migration;

/**
 * Class m180105_132513_import_8Str_base
 */
class m180105_132513_import_8Str_base extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        echo "import 8str base from dump \n";
        echo system('mysql < ' . dirname(__FILE__) . DIRECTORY_SEPARATOR
            . 'files' . DIRECTORY_SEPARATOR . "8str_full_base.sql");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->db->createCommand('DROP DATABASE `fbkru_0_8str`');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180105_132513_import_8Str_base cannot be reverted.\n";

        return false;
    }
    */
}
