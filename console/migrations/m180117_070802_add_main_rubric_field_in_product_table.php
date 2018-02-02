<?php

use common\modules\catalog\models\Product;
use yii\db\Migration;

/**
 * Class m180117_070802_add_main_rubric_field_in_product_table
 */
class m180117_070802_add_main_rubric_field_in_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'main_rubric_id', $this->integer(11)->null());
        $this->addForeignKey(
            'product_main_rubric_id_product_rubric_fk',
            'product',
            'main_rubric_id',
            'product_rubric',
            'id',
            'SET NULL',
            'CASCADE'
        );
        foreach (Product::find()->with('rubrics')->each() as $product) {
            if (isset($product->rubrics[0])) {
                echo "update {$product->id}: {$product->name}\n";
                /** @var Product $product */
                $product->main_rubric_id = $product->rubrics[0]->id;
                $product->modifier_id = 1;
                $product->save();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_main_rubric_id_product_rubric_fk', 'product');
        $this->dropColumn('product', 'main_rubric_id');
    }
}
