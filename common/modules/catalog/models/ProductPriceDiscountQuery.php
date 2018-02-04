<?php

namespace common\modules\catalog\models;

use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[ProductPriceDiscount]].
 *
 * @see ProductPriceDiscount
 *
 */
class ProductPriceDiscountQuery extends BaseDefaultQuery
{
    public function createCommand($db = NULL){
        return parent::createCommand($db); // TODO: Change the autogenerated stub
    }

    public function active() {
        return $this->andWhere(['status' => ProductPriceDiscount::STATUS['ACTIVE'],])
            ->andWhere(['<', 'active_from', new Expression('NOW()')])
            ->andWhere([
                'or', ['>', 'active_to', new Expression('NOW()')],
                ['is', 'active_to', null]
            ]);
    }

    protected function _prepareQuery() {
        if (!$this->forFrontEnd) return;
        $this->active();
    }
}