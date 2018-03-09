<?php

namespace common\modules\catalog\models\forms;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\Product2ProductRubric;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductBrandQuery;
use common\modules\catalog\models\ProductPrice;
use common\modules\catalog\models\ProductPriceDiscountQuery;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductTagQuery;
use common\modules\catalog\Module;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class ProductFilterForm
 */
class ProductFilterForm extends Model
{
    /**
     * Catalog path
     *
     * @var string|null $catalogPath
     */
    public $catalogPath;

    /**
     * Brand alias
     *
     * @var string|null $brand
     */
    public $brand;

    /**
     * Lower limit for filtering products at prices
     *
     * @var int|null $from
     */
    public $from;

    /**
     * Higher limit for filtering products at prices
     *
     * @var int|null $to
     */
    public $to;

    /**
     * Rubrics
     *
     * @var ProductRubric[]|array|null
     */
    private $rubrics;

    /**
     * Brands
     *
     * @var ProductBrand[]|array|null
     */
    private $brands;

    /**
     * Price range
     *
     * @var array|null
     */
    private $priceRange;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['catalogPath', 'brand'], 'safe'],
            [['from', 'to'], 'double']
        ];
    }

    /**
     * Make product provider
     *
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     */
    public function makeProductsProvider() {
        /** @var Module $catalog */
        $catalog = \Yii::$app->getModule('catalog');

        // Creates the query of products
        $productQuery = Product::find();

        // Specifies the relations
        $productQuery->with([
            'tags' => function (ProductTagQuery $q) {
                $q->with([
                    'productPriceDiscounts' => function (ProductPriceDiscountQuery $q) {
                        $q->orderBy(['weight' => SORT_ASC, 'created_at' => SORT_DESC]);
                    }
                ]);
            }, 'prices', 'brand', 'mainRubric', 'oldPrice', 'rubrics', 'price'
        ]);

        // Only active products
        $productQuery->andWhere([
            'product.status' => Product::STATUS['ACTIVE'],
        ]);

        // Filters by the rubric
        if ($this->catalogPath) {
            // Defines the rubric
            $rubric = $catalog->getRubricByPath($this->catalogPath);
            if (!$rubric) {
                throw new NotFoundHttpException('Путь не найден');
            }

            // Collects rubric identifiers
            $rubricsIds = $rubric->children()->select('id')->indexBy('id')->asArray()->column();

            // Appends the rubric
            $rubricsIds[$rubric->id] = $rubric->id;

            // Selects all products
            $productQuery->select('product.*')
                ->joinWith('rubrics r', false)
                ->joinWith('mainRubric mr', false)
                ->andWhere(['or',
                    ['in', 'r.id', $rubricsIds],
                    ['in', 'mr.id', $rubricsIds],
                ])
                ->groupBy('product.id');
        } else {
            $rubric = ProductRubric::find()->roots()->one();
        }

        // Filters products by alias of the brand
        if (isset($this->brand)) {
            $productQuery->joinWith(['brand' => function ($q) {
                /** @var ProductBrandQuery $q */
                $q->alias('brand');
            }]);

            $productQuery->andWhere(['brand.alias' => $this->brand]);
        }

        // Selects the range
        $this->priceRange = ProductPrice::find()->alias('price')
            // Only active
            ->andWhere(['<=', 'price.active_from', new Expression('NOW()')])
            ->andWhere('price.status="active"')
            // Select max and min
            ->select(new Expression('max(price.value) as max, min(price.value) as min'))
            // Filters by products
            ->andWhere(['in', 'price.product_id', (clone $productQuery)->select('product.id')])
            ->asArray()
            ->one();

        // Filter by price
        $productQuery->leftJoin(ProductPrice::tableName() . ' as price', 'price.product_id = product.id');
        $productQuery->andWhere(['<=', 'price.active_from', new Expression('NOW()')])
            ->andWhere('price.status="active"');
        $productQuery->andFilterWhere(['between', 'price.value', $this->from, $this->to]);

        // Find all children of the rubric
        $this->rubrics = $rubric->find()
            ->from([
                ProductRubric::tableName() . ' as rubric',
                ProductRubric::tableName() . ' as parentRubric',
                'product' => $productQuery
            ])
            // Children
            ->andWhere([
                'and',
                ['>', 'rubric.left_key', $rubric->left_key],
                ['<', 'rubric.right_key', $rubric->right_key],
            ])
            ->select('parentRubric.*')
            // Calculate the quantity
            ->addSelect(new Expression('count(`product`.`name`) as product_quantity'))
            ->leftJoin(Product2ProductRubric::tableName() . ' rlink', '`rlink`.`product_id` = `product`.`id`')
            ->andWhere([
                'and',
                'rubric.id = rlink.rubric_id',
                'rubric.left_key BETWEEN parentRubric.left_key AND parentRubric.right_key',
                ['>', 'parentRubric.left_key', $rubric->left_key],
                ['<', 'parentRubric.right_key', $rubric->right_key],
            ])
            ->groupBy('parentRubric.id')
            ->all();

        // Sort rubrics
        uasort($this->rubrics, function ($a, $b) {
            /**
             * @var ProductRubric $a
             * @var ProductRubric $b
             */
            return $a->left_key <=> $b->left_key;
        });

        // Select brands of the rubric
        $brandQuery = clone $productQuery;
        $brandIds = $brandQuery->select('product.brand_id as id')->distinct()->column();
        $this->brands = ProductBrand::find()->alias('brand')
            ->select('brand.*')
            ->addSelect(new Expression('count(product.id) as product_quantity'))
            ->rightJoin(['product' => $productQuery], 'product.brand_id=brand.id')
            ->groupBy('brand.id')
            ->where(['in', 'brand.id', $brandIds])
            ->all();

        // Ordering
        $productQuery->orderBy(['product.on_list_top' => SORT_ASC, 'product.main_rubric_id' => SORT_ASC]);

        return new ActiveDataProvider([
            'query' => $productQuery,
        ]);
    }

    /**
     * Get rubrics
     *
     * @return array|ProductRubric[]|null
     */
    public function getRubrics() {
        return $this->rubrics;
    }

    /**
     * Get brands
     *
     * @return array|ProductBrand[]|null
     */
    public function getBrands() {
        return $this->brands;
    }

    /**
     * Get the range of the price
     *
     * @return array
     *
     * ```
     * ['range' => ['min' => 0, 'max' => 10], 'start' => ['min' => 0, 'max' => 10]]
     * ```
     */
    public function getPriceRange() {
        $startMin = $this->from;
        if (empty($startMin)) {
            $startMin = $this->priceRange['min'];
        }

        $startMax = $this->to;
        if (empty($startMax)) {
            $startMax = $this->priceRange['max'];
        }

        return [
            'start' => [
                'min' => $startMin,
                'max' => $startMax,
            ],
            'range' => $this->priceRange
        ];
    }

    /**
     * Make the brand uri
     *
     * @param ProductBrand $brand
     * @return string
     */
    public function makeBrandUri(ProductBrand $brand) {
        return Url::to(['/catalog/default/index', 'brand' => $brand->alias, 'catalogPath' => $this->catalogPath]);
    }

    /**
     * Make the rubric uri
     *
     * @param ProductRubric $rubric
     * @return string
     */
    public function makeRubricUri(ProductRubric $rubric) {
        return Url::to(['/catalog/default/index', 'brand' => $this->brand, 'catalogPath' => $rubric->material_path]);
    }
}