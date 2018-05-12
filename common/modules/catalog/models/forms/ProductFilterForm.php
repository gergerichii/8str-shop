<?php

namespace common\modules\catalog\models\forms;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\Product2ProductRubric;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\queries\ProductBrandQuery;
use common\modules\catalog\models\ProductPrice;
use common\modules\catalog\models\queries\ProductPriceDiscountQuery;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductSphinxIndex;
use common\modules\catalog\models\queries\ProductTagQuery;
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
     * Product name query string
     *
     * @var string|null $nameQuery
     */
    public $nameQuery;
    
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
    
    public $order_param;
    public $sort;

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
            [['nameQuery', 'catalogPath', 'brand'], 'safe'],
            ['order_param', 'in', 'range' => ['default', 'price', 'name'], 'skipOnEmpty' => true],
            ['order_param', 'default', 'value' => 'default', 'when' => function(Model $m){
                if ($m->hasErrors('order_param')) {
                    $m->order_param = '';
                }
                return true;
            }, 'skipOnError' => false],
            ['sort', 'in', 'range' => ['asc', 'desc'], 'skipOnEmpty' => true],
            ['sort', 'default', 'value' => 'asc', 'when' => function(Model $m){
                if ($m->hasErrors('sort')) {
                    $m->sort = '';
                }
                return true;
            }, 'skipOnError' => false],
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
        
        // Search for rubrics
        /** @var ProductRubric $root */
        $root = null;
        if ($sc = \Yii::$app->request->get('sc')) {
            $root = ProductRubric::find()->where(['id' => $sc])->one();
        } elseif ($this->catalogPath) {
            // Defines the rubric
            $root = $catalog->getRubricByPath($this->catalogPath);
        }
        if (!$root) {
            /** @var ProductRubric $root */
            $root = ProductRubric::find()->roots()->one();
        }
        
        // Creates the query of products
        $productQuery = Product::find();
    
        if (!empty($sk = \Yii::$app->request->get('sk'))) {
            $allChildRubrics = $root->children()->select('id')->asArray()->column();
            $allChildRubrics = array_map(function ($v) {return intval($v);}, $allChildRubrics);
            array_unshift($allChildRubrics, $root->id);
            $productIndexQuery = ProductSphinxIndex::find()
                ->select('id')
                ->where(['rubric_id' => $allChildRubrics]);
            $sk = Yii::$app->sphinx->quoteValue(trim($sk));
            $sk = str_replace('/', '\/', $sk);
            $productIndexQuery->match(new Expression(':match', ['match' => "{$sk}"]));
    
            $productsIds = $productIndexQuery->limit(20000)->column();
            $productQuery->where(['[[product]].[[id]]' => $productsIds]);
        }

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
        $productQuery->active();

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
            array_unshift($rubricsIds, $rubric->id);

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
        
        if ($this->brand && $brand = ProductBrand::findOne(['name' => $this->brand])) {
            $this->brand = $brand->alias;
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
        $productQuery->leftJoin(
            ['price' => ProductPrice::tableName()],
            [
                'and',
                '[[price]].[[product_id]] = [[product]].[[id]]',
                ['<=', '[[price]].[[active_from]]', new Expression('NOW()')],
                ['[[price]].[[status]]' => 'active'],
                ['[[price]].[[domain_name]]' => \Yii::$app->params['domain']],
            ]
        );
        $productQuery->andFilterWhere(['between', 'price.value', $this->from, $this->to]);

        // Find all children of the rubric
        $this->rubrics = $root->find()
            ->from([
                ProductRubric::tableName() . ' as rubric',
                ProductRubric::tableName() . ' as parentRubric',
                'product' => $productQuery
            ])
            // Children
            ->andWhere([
                'and',
                ['>', 'rubric.left_key', $root->left_key],
                ['<', 'rubric.right_key', $root->right_key],
            ])
            ->select('parentRubric.*')
            // Calculate the quantity
            ->addSelect(new Expression('count(`product`.`name`) as product_quantity'))
            ->leftJoin(Product2ProductRubric::tableName() . ' rlink', '`rlink`.`product_id` = `product`.`id`')
            ->andWhere([
                'and',
                [
                    'or',
                    'rubric.id = rlink.rubric_id',
                    'rubric.id = product.main_rubric_id'
                ],
                'rubric.left_key BETWEEN parentRubric.left_key AND parentRubric.right_key',
                ['>', 'parentRubric.left_key', $root->left_key],
                ['<', 'parentRubric.right_key', $root->right_key],
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
        $sort = ($this->sort === 'desc') ? SORT_DESC : SORT_ASC;
        switch($this->order_param) {
            case 'name':
                $productQuery->orderBy(['[[product]].[[name]]' => $sort]);
                break;
            case 'price':
                $productQuery->orderBy(['[[price]].[[value]]' => $sort]);
                break;
            default:
                $productQuery->orderBy(['[[product]].[[on_list_top]]' => SORT_ASC, '[[product]].[[main_rubric_id]]' => SORT_ASC]);
        }

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
        $params = \Yii::$app->request->get();
        $params['brand'] = $brand->alias;
        $params['catalogPath'] = $this->catalogPath;
        array_unshift($params, '/catalog/default/index');
        return Url::toRoute($params);
    }

    /**
     * Make the rubric uri
     *
     * @param ProductRubric $rubric
     * @return string
     */
    public function makeRubricUri(ProductRubric $rubric) {
        $params = \Yii::$app->request->get();
        $params['brand'] = $this->brand;
        $params['catalogPath'] = $rubric->material_path;
        array_unshift($params, '/catalog/default/index');
        return Url::toRoute($params);
    }
}