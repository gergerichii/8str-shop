<?php

use common\models\entities\ProductRubric;
use yii\db\Migration;

/**
 * Class m180117_084708_add_material_path_field_to_rubric_table
 */
class m180117_084708_add_material_path_field_to_rubric_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product_rubric', 'material_path', $this->string(500)->null());
        foreach(ProductRubric::find()->each() as $rubric) {
            /** @var ProductRubric $rubric */
            $rubric->material_path = \Yii::$app->get('catalog')->getRubricPath($rubric, false);
            echo "Update {$rubric->name}, path: {$rubric->material_path} \n";
            $rubric->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product_rubric', 'material_path');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180117_084708_add_material_path_field_to_rubric_table cannot be reverted.\n";

        return false;
    }
    */
}
