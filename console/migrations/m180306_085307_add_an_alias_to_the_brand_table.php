<?php

use common\modules\catalog\models\ProductBrand;
use yii\db\Migration;

/**
 * Class m180306_085307_add_an_alias_to_the_brand_table
 */
class m180306_085307_add_an_alias_to_the_brand_table extends Migration
{

    const TABLE_NAME = '{{%product_brand}}';

    /**
     * @inheritdoc
     */
    public function safeUp() {
        $this->addColumn(self::TABLE_NAME, '[[alias]]', $this->string(150));

        $brands = ProductBrand::find()->all();
        foreach ($brands as $brand) {
            if (false === $brand->save()) {
                return false;
            }
        }

        $this->alterColumn(self::TABLE_NAME, '[[alias]]', $this->string(150)->unique()->notNull());
        $this->createIndex('product_brand_alias_index', self::TABLE_NAME, '[[alias]]', true);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropIndex('product_brand_alias_index', self::TABLE_NAME);
        $this->dropColumn(self::TABLE_NAME, '[[alias]]');
    }
}
