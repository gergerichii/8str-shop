<?php

namespace common\modules\catalog\providers;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductSphinxIndex;
use yii\data\BaseDataProvider;
use yii\db\Expression;

/**
 * Class FrontendSearchProvider
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class FrontendSearchProvider extends BaseDataProvider
{
    /**
     * Query
     * @var string $q
     */
    public $q;

    /**
     * Rubric id
     * @var int
     */
    public $rubric;

    /**
     * Rubric collection
     * @var array|null
     */
    private $_rubrics;

    /**
     * Brand collection
     * @var array|null
     */
    private $_brands;

    /**
     * Product collection
     * @var array|null
     */
    private $_products;

    /**
     * Amount of rubrics
     * @var int
     */
    private $_amountOfRubrics = 0;

    /**
     * Amount of brands
     * @var int
     */
    private $_amountOfBrands = 0;

    /**
     * Amount of products
     * @var int
     */
    private $_amountOfProducts = 0;

    /**
     * Whether is top
     * @var bool
     */
    private $_top = false;

    /**
     * Set for top
     */
    public function top() {
        $this->_top = true;
    }

    /**
     * Prepares the data models that will be made available in the current page.
     * @return array the available data models
     */
    protected function prepareModels() {
        // $pagination = $this->getPagination();
        // TODO Add pagination

        // Search for rubrics
        /** @var ProductRubric $root */
        $root = null;
        if ($this->rubric) {
            $root = ProductRubric::find()->where(['id' => $this->rubric])->one();
        }

        if (!$root) {
            /** @var ProductRubric $root */
            $root = ProductRubric::find()->roots()->one();
        }

        if (!$root) {
            return [];
        }
        
        $rubricQuery = $root->children()
            ->andFilterWhere(['like', 'name', $this->q]);

        if ($this->_top) {
            $rubricQuery->limit(5);
        }

        $this->_rubrics = $rubricQuery->all();
        $this->_amountOfRubrics = count($this->_rubrics);

        // Search for brands
        $brandQuery = ProductBrand::find()->select("`id`, `name`, 'brand' as `type`")
            ->andFilterWhere(['like', 'name', $this->q]);

        if ($this->_top) {
            $brandQuery->limit(5);
        }

        $this->_brands = $brandQuery->all();
        $this->_amountOfBrands = count($this->_brands);

        // Search for products
        $this->_products = [];
        $this->q = \Yii::$app->sphinx->escapeMatchValue(trim($this->q));
        
        $productIndexQuery = ProductSphinxIndex::find()
            ->match(new Expression(':match', ['match' => "@(name) {$this->q} | @(description) {$this->q}"]))
            ->select('id');
        
        $allChildRubrics = $root->children()->select('id')->asArray()->column();
        array_unshift($allChildRubrics, $root->id);
        if (!empty($this->rubric)) {
            $productIndexQuery->where(['rubric_id' => $allChildRubrics]);
        }

        if ($this->_top) {
            $productIndexQuery->limit(5);
        }

        $productsIndexes = $productIndexQuery->column();
        if ($productsIndexes) {
            $productQuery = Product::find()
                ->andFilterWhere(['in', 'id', $productsIndexes]);

            if ($this->_top) {
                $productQuery->limit(5);
            }

            $this->_products = $productQuery->all();
            $this->_amountOfProducts = count($this->_products);
        }

        $models = array_merge($this->_rubrics, $this->_brands, $this->_products);

        return $models;
    }

    /**
     * Prepares the keys associated with the currently available data models.
     * @param array $models the available data models
     * @return array the keys
     */
    protected function prepareKeys($models) {
        return array_keys($models);
    }

    /**
     * Returns a value indicating the total number of data models in this data provider.
     * @return int total number of data models in this data provider.
     */
    protected function prepareTotalCount() {
        return $this->_amountOfRubrics + $this->_amountOfBrands + $this->_amountOfProducts;
    }

    /**
     * Get products
     * @return array|null
     */
    public function getProducts() {
        return $this->_products ?? [];
    }

    /**
     * Get rubrics
     * @return array|null
     */
    public function getRubrics() {
        return $this->_rubrics ?? [];
    }

    /**
     * Get brands
     * @return array|null
     */
    public function getBrands() {
        return $this->_brands ?? [];
    }
}