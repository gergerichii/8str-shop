<?php

namespace common\modules\catalog\widgets;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductTagQuery;
use yii\base\Widget;
use common\behaviors\CacheableWidgetBehavior;
use yii\caching\ChainedDependency;
use yii\caching\DbDependency;
use yii\caching\TagDependency;

/**
 * Class ProductTagWidget
 */
class ProductTagWidget extends Widget
{
    /** @var string $tagName Filter products by tag name */
    public $tagName = '';

    /** @var int $limit Product limit */
    public $limit = 9;

    /** @var string $view View */
    public $viewName = 'productTagWidget';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => CacheableWidgetBehavior::className(),
                'cacheDependency' => [
                    'class' => ChainedDependency::class,
                    'dependencies' => [
                        new DbDependency(['sql' => 'SELECT MAX(modified_at) FROM product']),
                        new TagDependency(['tags' => Product::class])
                    ]
                ],
                'cacheKeyVariations' => [
                    'tagName' => $this->tagName,
                    'limit' => $this->limit,
                    'view' => $this->viewName
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function run() {
        if (empty($this->tagName)) {
            return '';
        }

        if ($this->limit < 1) {
            $this->limit = 1;
        }

        $products = Product::find()->alias('products')
            ->joinWith([
                'tags' => function ($q) {
                    /** @var ProductTagQuery $q */
                    $q->alias('tags');
                },
            ])
            ->with(['price', 'oldPrice', 'rubrics', 'mainRubric'])
            ->where(['tags.name' => $this->tagName])
            ->limit($this->limit)
            ->all();

        if (!$products) {
            return '';
        }

        $catalog = \Yii::$app->getModule('catalog');

        return $this->render($this->viewName, ['products' => $products, 'catalog' => $catalog]);
    }
}