<?php

use yii\db\Migration;

/**
 * Class m180525_084222_add_tech_desc_column_to_product
 */
class m180525_084222_add_tech_desc_column_to_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product}}', '[[tech_desc]]', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product}}', '[[tech_desc]]');
    }

}
