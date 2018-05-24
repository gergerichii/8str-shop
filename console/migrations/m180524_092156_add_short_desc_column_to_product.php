<?php

use yii\db\Migration;

/**
 * Class m180524_092156_add_short_desc_column_to_product
 */
class m180524_092156_add_short_desc_column_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', '[[short_desc]]', $this->string(500)->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', '[[short_desc]]');
    }
}
