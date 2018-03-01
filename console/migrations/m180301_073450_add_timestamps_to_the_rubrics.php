<?php

use yii\db\Migration;

/**
 * Class m180301_073450_add_timestamps_to_the_rubrics
 */
class m180301_073450_add_timestamps_to_the_rubrics extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn('product_rubric', '[[created_at]]', $this->timestamp()->notNull()->defaultExpression('NOW()'));
        $this->addColumn('product_rubric', '[[modified_at]]', $this->timestamp()->null()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropColumn('product_rubric', '[[created_at]]');
        $this->dropColumn('product_rubric', '[[modified_at]]');
    }
}
