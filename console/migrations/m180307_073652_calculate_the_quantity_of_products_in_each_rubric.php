<?php

use yii\db\Migration;

/**
 * Adds `product_quantity` column and calculate the quantity of products in each rubric
 *
 * Class m180307_073652_calculate_the_quantity_of_products_in_each_rubric
 */
class m180307_073652_calculate_the_quantity_of_products_in_each_rubric extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('{{%product_rubric}}', 'product_quantity', $this->integer(11)->defaultValue(0));

        // Recalculate the quantity of products
        /*$this->getDb()->createCommand('UPDATE product_rubric' .
            ' LEFT JOIN (' .
            'SELECT parent.id, COUNT(product.name) as product_quantity' .
            ' FROM product_rubric AS node, product_rubric AS parent, product' .
            ' LEFT JOIN product2product_rubric as rlink ON rlink.product_id = product.id' .
            ' WHERE node.left_key BETWEEN parent.left_key AND parent.right_key AND node.id = rlink.rubric_id' .
            ' GROUP BY parent.id' .
            ') calculated' .
            ' ON calculated.id = product_rubric.id' .
            ' SET product_rubric.product_quantity = calculated.product_quantity')->execute();*/

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('{{%product_rubric}}', 'product_quantity');
        return true;
    }
}
