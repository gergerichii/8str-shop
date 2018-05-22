<?php

namespace common\modules\catalog\models\queries;

use common\base\models\BaseDefaultQueryTrait;
use common\base\models\nestedSets\NSActiveQuery;
use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductRubric;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 *
 */
class ProductRubricQuery extends NSActiveQuery
{
    use BaseDefaultQueryTrait;
    
    public $withProductsCountsInName = false;
    
    public function active() {
        return $this->andWhere([
            'productRubric.active' => 1,
        ]);
    }
    
    public function withProductsCountsInName() {
        $this->withProductsCountsInName = true;
        return $this;
    }
    
    /**
     * @param $models
     *
     * @return array|mixed
     * @throws \Throwable
     */
    protected function _prepareModels($models) {
        
        if (!empty($models) && $this->withProductsCountsInName) {
            $is_array = is_array($models);
            if (!$is_array) {
                $models = [$models];
            }
            
            $productCounts = ProductRubric::getProductsCounts();
            foreach($models as $model) {
                $count = (isset($productCounts[$model->id])) ? $productCounts[$model->id] : '-';
                $model->name = "{$model->name} ({$count})";
            }
            
            if (!$is_array) {
                $models = $models[0];
            }
        }
        
        return $models;
    }
    
    protected function _prepareQuery() {
    }
}
