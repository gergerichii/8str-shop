<?php

namespace common\modules\catalog\behaviors;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductRubric;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Class ProductQuantityBehavior
 */
class ProductQuantityBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'increaseQuantity',
            ActiveRecord::EVENT_AFTER_DELETE => 'reduceQuantity'
        ];
    }

    public function increaseQuantity() {
        $rubricIds = $this->getRelatedRubricIds();
        \Yii::$app->getDb()->createCommand()->update(ProductRubric::tableName(), new Expression('product_rubric=product_rubric+1'), ['in', 'id', $rubricIds]);
    }

    public function reduceQuantity() {
        $rubricIds = $this->getRelatedRubricIds();
        \Yii::$app->getDb()->createCommand()->update(ProductRubric::tableName(), new Expression('product_rubric=product_rubric-1'), ['in', 'id', $rubricIds]);
    }

    /**
     * Get the related rubric identifiers
     * @return array
     */
    private function getRelatedRubricIds() {
        /** @var Product $product */
        $product = $this->owner;
        /** @var ProductRubric[] $rubrics */
        $rubrics = $product->getRubrics()->indexBy('id')->all();
        $rubricIds = array_keys($rubrics);
        foreach ($rubrics as $rubric) {
            $parentRubricIds = $rubric->parents()->select('id')->column();
            $rubricIds = array_merge($rubricIds, $parentRubricIds);
        }

        return array_unique($rubricIds);
    }
}