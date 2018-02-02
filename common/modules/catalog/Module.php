<?php

namespace common\modules\catalog;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductRubric;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * catalog module definition class
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    protected $productActionId = 'product';

    protected $_products = [];
    protected $_rubrics = [];

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'common\modules\catalog\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap ($app)
    {
        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        $rules = [
            [
                'pattern' => 'catalog/<catalogPath:[\w\-\.,/_]+?>/<productId:\d+>',
                'route' => '/catalog/default/product',
                'encodeParams' => false,
            ],
            [
                'pattern' => 'catalog/<catalogPath:[\w\-\.,/_]*?>',
                'route' => '/catalog/default/index',
                'encodeParams' => false,
            ],
            'catalog' => 'catalog/default/index/',
        ];
        if (count($urlManagers)) {
            foreach ($urlManagers as $urlManager) {
                /** @var \yii\web\UrlManager $urlManager */
                $urlManager = $app->get($urlManager);
                $urlManager->addRules($rules);
            }
        } else {
            $app->urlManager->addRules($rules);
        }
    }
    /**
     * @param ProductRubric|string|null $rubric
     * @param Product|int|null $product
     *
     * @return string
     * @throws \yii\base\ErrorException
     */
    public function getCatalogUri($rubric=null, $product=null) {
        $uriParams = ["/$this->id/$this->defaultRoute"];

        if (is_int($product)) {
            if (isset($this->_products[$product])) {
                $product = $this->_products[$product];
            } else {
                $this->_products[] = $product = Product::find()
                    ->with(['main_rubric', 'rubrics'])->active()->where(['id' => $product])->one();
            }
        }
        if ($product instanceof Product) {
            $uriParams['productId'] = $product->id;
            $uriParams[0] .= '/' . $this->productActionId;
        } elseif (!is_null($product)) {
            throw new ErrorException('$product must be integer or instance of common\modules\catalog\models\Product');
        } else {
            $uriParams[0] .= '/index';
        }

        if (is_string($rubric) || $rubric instanceof ProductRubric || is_null($rubric)) {
            $rubricPath = '';
            if ($rubric instanceof ProductRubric) {
                $rubricPath = $this->getRubricPath($rubric, false);
            }
            if ($product && ($rubricPath || !$this->productHasRubric($product, $rubricPath))) {
                $rubricPath = $this->getRubricPath($product->mainRubric, false);
            }
            $uriParams['catalogPath'] = $rubricPath;
        } else {
            throw new ErrorException('$rubric must be string or instance of common\modules\catalog\models\ProductRubric');
        }
        return str_replace('%2F', urldecode('%2F'), Url::to($uriParams));
    }

    /** ---------------------------------Продукты------------------------------------*/

    /**
     * @param Product $product
     * @return string
     *
     * TODO: Доделать чтобы автоматом определялась текущая рубрика
     */
    public function getProductPath(Product $product) {
        $path = $this->getRubricPath($product->mainRubric, false);
        return $path;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param string                        $rubricPath
     *
     * @return bool
     */
    public function productHasRubric(Product $product, string $rubricPath) {
        if ($product->mainRubric->material_path == $rubricPath) return true;
        foreach ($product->rubrics as $rubric) {
            if ($rubric->material_path == $rubricPath) return true;
        }

        return false;
    }

    /**
     *
     * @param \common\modules\catalog\models\Product $product
     * @param bool                          $format
     *
     * @return integer|string
     */
    public function priceOf(Product $product, $format = true) {
        $price = $this->_applyDiscounts($product)['price'];
        if ($format) {
            $price = $this->_formatCurrency($price);
        }
        return $price;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     *
     * @return \common\modules\catalog\models\ProductPrice|null
     */
    public function priceObjectOf(Product $product) {
        return isset($product->prices[0]) ? $product->prices[0] : null;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool                          $format
     *
     * @return integer|string
     */
    public function oldPriceOf(Product $product, $format = true) {
        $price = $this->_applyDiscounts($product)['oldPrice'];
        if ($format) {
            $price = $this->_formatCurrency($price);
        }
        return $price;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool                          $format
     *
     * @return \common\modules\catalog\models\ProductPrice|null
     */
    public function oldPriceObjectOf(Product $product, $format = true) {
        return isset($product->prices[1]) ? $product->prices[1] : null;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool                          $format
     *
     * @return integer|string
     */
    public function discountOf(Product $product, $format = true) {
        $discount = $this->_applyDiscounts($product)['discount'];
        if ($discount && $format) {
            $discount = $this->_formatPercent($discount);
        }
        return $discount;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool                          $format
     *
     * @return \common\modules\catalog\models\ProductPriceDiscount[]
     */
    public function activeDiscountsOf(Product $product, $format = true) {
        return $this->_applyDiscounts($product)['activeDiscounts'];
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     *
     * @return array
     */
    protected function _applyDiscounts(Product $product) {
        if (isset($this->_products[$product->id])) {
            return $this->_products[$product->id];
        }

        $price = isset($product->prices[0]) ? $product->prices[0]->value : 0;
        $oldPrice = isset($product->prices[1]) ? $product->prices[1]->value : 0;

        /** TODO: Сделать чтобы был механизм управляемого сложения или взаимоисключения скидок */
        $discounts = [];
        foreach ($product->tags as $tag) {
            foreach($tag->productPriceDiscounts as $discount) {
                if ($discount->isActive) $discounts[$discount->id] = $discount;
            }
        }
        if (!empty($discounts)) {
            $discount = 0;
            ArrayHelper::multisort($discounts, 'weight', SORT_DESC);
            $discountsValues = ArrayHelper::map($discounts, 'id', 'value', 'weight');
            $discountsValues = array_shift($discountsValues);
            foreach ($discountsValues as $value) {
                $discount += $value;
            }
            $discount /= -100;

            $oldPrice = $price;
            $price *= -$discount;
        } else {
            $discount = ($price < $oldPrice) ? -$price / $oldPrice : 0;
        }
        $activeDiscounts = [];
        return $this->_products[$product->id] = compact('price', 'oldPrice', 'discount', 'activeDiscounts');
    }

    /**
     * @param float $value
     *
     * @return string
     */
    protected function _formatCurrency($value) {
        return \Yii::$app->formatter->asDecimal($value, 0) . ' руб.';
    }

    /**
     * @param float $value
     *
     * @return string
     */
    protected function _formatPercent($value) {
        return \Yii::$app->formatter->asPercent($value, 0);
    }


    /** ---------------------------------Рубрики------------------------------------ */
    /**
     * @param ProductRubric $rubric
     * @param bool $asArray
     * @return array|string
     *
     */
    public function getRubricPath(ProductRubric $rubric, $asArray = true) {
        if (empty($rubric->material_path)) {
            $path = [];
            foreach ($rubric->parents()->each() as $pRubric) {
                $path[] = $pRubric->slug;
            }
            $path[] = $rubric->slug;

            if (!$asArray) {
                $path = implode('/', $path);
            }
        } else {
            if ($asArray) {
                $path = explode('/', $rubric->material_path);
            } else {
                $path = $rubric->material_path;
            }
        }

        return $path;
    }

    /**
     * @param string|ProductRubric
     * @param Product|integer|string|null $product
     *
     * @return array
     * @throws \yii\base\ErrorException
     */
    public function getBreadcrumbs($target, $product = null) : array {
        if (is_string($target)) {
            $catalogPath = $target;
        } elseif($target instanceof ProductRubric) {
            $catalogPath = $this->getRubricPath($target, false);
        } else {
            $catalogPath = '';
        }

        $breadcrumbs = [];
        if ( $catalogPath && $currentRubric = $this->getRubricByPath($catalogPath)) {
            $rubricsPath = $currentRubric->parents()->all();
            $rubricsPath[] = $currentRubric;
            /** @var ProductRubric $rubric */
            foreach ($rubricsPath as $rubric) {
                $label = (string) $rubric;
                $breadcrumbs[] = [
                    'label' => $label,
                    'url' => $this->getCatalogUri($rubric),
                ];
            }
        }

        if (!empty($product)) {
            if (is_int($product)) {
                $product = Product::findOne($product);
            }
            $productLabel = (string) $product;

            if ($productLabel) {
                $breadcrumbs[] = [
                    'label' => (string) $product,
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * @param string $path
     * @return bool|ProductRubric
     */
    public function getRubricByPath(string $path) {
        if (isset($this->_rubrics[$path])) return $this->_rubrics[$path];
        if ($rubric = ProductRubric::findOne(['material_path' => $path])) {
            return $this->_rubrics[$path] = $rubric;
        }
        return $this->_rubrics[$path] = false;
    }

    /**
     * @param $depth
     *
     * @return array
     *
     * TODO: Доделать для уровней вложенности
     * @throws \yii\base\ErrorException
     */
    public function getMenuStructure($depth) {
        $menuObj = ProductRubric::find()->roots()->all();
        $menu = [];
        foreach ($menuObj as $obj){
            $mItem = [];
            $mItem['label'] = (string) $obj;
            $mItem['url'] = $this->getCatalogUri($obj);
            $menu[] = $mItem;
        }

        return $menu;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param string                        $tagName
     *
     * @return bool
     */
    public function productHasTag(Product $product, string $tagName): bool {
        return !empty($product->tags[$tagName]);
    }
}
