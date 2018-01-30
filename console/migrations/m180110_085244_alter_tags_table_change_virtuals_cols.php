<?php

use yii\db\Migration;

/**
 * Class m180110_085244_alter_tags_table_change_virtuals_cols
 */
class m180110_085244_alter_tags_table_change_virtuals_cols extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
//        $this->db->createCommand(
//            'ALTER TABLE `product_tag` ALTER `show_at` DROP DEFAULT, ALTER `hide_at` DROP DEFAULT;'
//        );
//        $this->db->createCommand(
//            'ALTER TABLE `product_tag`
//CHANGE COLUMN `show_at` `show_at` INT(11) AS (\'$.showAt\') VIRTUAL AFTER `add_data`,
//CHANGE COLUMN `hide_at` `hide_at` INT(11) AS (\'$.hideAt\') VIRTUAL AFTER `show_at`;'
//        );
        $this->alterColumn('{{%product_tag}}', 'show_at',
            $this->integer(11)->append("AS (JSON_EXTRACT([[add_data]], '$.showAt')) VIRTUAL")
        );
        $this->alterColumn('{{%product_tag}}', 'hide_at',
            $this->integer(11)->append("AS (JSON_EXTRACT([[add_data]], '$.hideAt')) VIRTUAL")
        );
        $this->alterColumn('{{%product_tag}}', 'image',
            $this->string(255)->append("AS (JSON_EXTRACT([[add_data]], '$.image')) VIRTUAL")
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180110_085244_alter_tags_table_change_virtuals_cols cannot be reverted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180110_085244_alter_tags_table_change_virtuals_cols cannot be reverted.\n";

        return false;
    }
    */
}
