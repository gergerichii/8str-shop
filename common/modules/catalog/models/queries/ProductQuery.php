<?php

namespace common\modules\catalog\models\queries;

use common\base\models\BaseDefaultQueryTrait;
use common\modules\catalog\models\Product;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 *
 */
class ProductQuery extends ActiveQuery
{
    use BaseDefaultQueryTrait;
    
    public function active() {
        return $this->andWhere([
            'product.status' => Product::STATUS['ACTIVE'],
        ]);
    }

    public function showOnHome() {
        return $this->andWhere(['show_on_home' => true]);
    }

    protected function _prepareQuery() {
        if ($this->forFrontEnd){
            $this->active()->with([
                'tags' => function(ProductTagQuery $q) {
                    $q->with([
                        'productPriceDiscounts' => function (ProductPriceDiscountQuery $q) {
                            $q->orderBy(['weight' => SORT_ASC, 'created_at' => SORT_DESC]);
                        }
                    ]);
                }, 'prices', 'brand', 'mainRubric'
            ])->orderBy(['on_list_top' => SORT_ASC, 'main_rubric_id' => SORT_ASC]);
        }
    }
}
