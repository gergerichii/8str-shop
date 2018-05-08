<?php

namespace common\modules\catalog;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductBrand;
use common\modules\catalog\models\ProductPrice;
use common\modules\catalog\models\ProductRubric;
use common\modules\catalog\models\ProductRubricMenuItems;
use common\modules\files\models\Image;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\caching\DbDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * catalog module definition class
 *
 * @property mixed $brandMenuStructure
 */
class Module extends \yii\base\Module
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
    public function init() {
        parent::init();

        // custom initialization code goes here
    }
    
    /**
     * @param ProductRubric|string|null $rubric
     * @param Product|int|null $product
     *
     * @return string
     * @throws \yii\base\ErrorException
     */
    public function getCatalogUri($rubric = null, $product = null) {
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
            if ($product && $product->main_rubric_id && ($rubricPath || !$this->productHasRubric($product, $rubricPath))) {
                $rubricPath = $this->getRubricPath($product->mainRubric, false);
            }
            $uriParams['catalogPath'] = $rubricPath;
        } else {
            throw new ErrorException('$rubric must be string or instance of common\modules\catalog\models\ProductRubric');
        }
        return str_replace('%2F', urldecode('%2F'), Url::toRoute($uriParams));
    }

    /**
     * Gets the brand uri
     *
     * @param ProductBrand $brand
     * @return string
     */
    public function getBrandUri(ProductBrand $brand) {
        return Url::to(['/catalog/default/index', 'brand' => $brand->alias]);
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
     * @param string $rubricPath
     *
     * @return bool
     */
    public function productHasRubric(Product $product, string $rubricPath) {
        if (!empty($product->main_rubric_id) && $product->mainRubric->material_path == $rubricPath) return true;
        foreach ($product->rubrics as $rubric) {
            if ($rubric->material_path == $rubricPath) return true;
        }

        return false;
    }

    /**
     *
     * @param \common\modules\catalog\models\Product $product
     * @param bool $format
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
        return isset($product->frontendPrices[0]) ? $product->frontendPrices[0] : null;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool $format
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
     * @param bool $format
     *
     * @return \common\modules\catalog\models\ProductPrice|null
     */
    public function oldPriceObjectOf(Product $product, $format = true) {
        return isset($product->frontendPrices[1]) ? $product->frontendPrices[1] : null;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool $format
     *
     * @return integer|string
     */
    public function discountOf(Product $product, $format = true) {
        $discount = $this->_applyDiscounts($product)['discount'];
        if ($discount && $format) {
            if ($discount < 0) {
                $discount = $this->_formatPercent((1+$discount) * -1);
            } else {
                $discount = $this->_formatPercent(1-$discount);
            }
        }
        return $discount;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param bool $format
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

        $price = 0;
        if ($product->price) {
            $price = $product->price->value;
        }

        $oldPrice = 0;
        if ($product->oldPrice) {
            $oldPrice = $product->oldPrice->value;
        }

        /** TODO: Сделать чтобы был механизм управляемого сложения или взаимоисключения скидок */
        $discounts = [];
        foreach ($product->tags as $tag) {
            foreach ($tag->productPriceDiscounts as $discount) {
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
        try {
            return Yii::$app->formatter->asCurrency($value);
        } catch (InvalidConfigException $e) {
            Yii::error($e->getMessage());
            return $value;
        }
    }

    /**
     * @param float $value
     *
     * @return string
     */
    protected function _formatPercent($value) {
        return Yii::$app->formatter->asPercent($value, 0);
    }


    /** ---------------------------------Рубрики------------------------------------ */
    /**
     * Gets the path to the rubric
     *
     * @param ProductRubric|int $rubric
     * @param bool $asArray
     * @return array|string
     */
    public function getRubricPath($rubric, $asArray = true) {
        if (is_int($rubric)) {
            $rubric = ProductRubric::findOne($rubric);
        }
        if (empty($rubric)) {
            return false;
        }
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
    public function getBreadcrumbs($target, $product = null): array {
        if (is_string($target)) {
            $catalogPath = $target;
        } elseif ($target instanceof ProductRubric) {
            $catalogPath = $this->getRubricPath($target, false);
        } else {
            $catalogPath = '';
        }

        $breadcrumbs = [];
        if ($catalogPath && $currentRubric = $this->getRubricByPath($catalogPath)) {
            $rubricsPath = $currentRubric->parents()->all();
            $rubricsPath[] = $currentRubric;
            /** @var ProductRubric $rubric */
            foreach ($rubricsPath as $rubric) {
                if ($rubric->level === 0) continue;
                $label = (string)$rubric;
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
            $productLabel = (string)$product;

            if ($productLabel) {
                $breadcrumbs[] = [
                    'label' => (string)$product,
                ];
            }
        }

        return $breadcrumbs;
    }

    /**
     * Get rubric by path
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
     * Get menu structure
     * @param int $depth
     * @param bool $showHidden Whether to show rubrics that are hidden on the home page
     * @return array
     */
    public function getMenuStructure($depth, $showHidden = false) {
        $cacheKey = __CLASS__ . '::' . __FUNCTION__ . '::' . $depth . '::' . $showHidden;
        $cache = Yii::$app->getCache();
//        $cache->flush();
//        $data = $cache->get($cacheKey);
//        if (false !== $data) {
//            return $data;
//        }

        /** @var ProductRubric $root */
        $root = ProductRubric::find()->roots()->one();
        if (!$root) {
            return [];
        }

        /** @var ProductRubric[] $rubrics */
        $query = $root->children($depth);
        if (!$showHidden) {
            $query->andWhere('visible_on_home_page=1');
        }

        $rubrics = $query->all();
        $module = $this;

        $menuItems = new ProductRubricMenuItems($rubrics, function ($rubric) use ($module) {
            /** @var ProductRubric $rubric */
            return [
                'label' => $rubric->name,
                'items' => null,
                'url' => $module->getCatalogUri($rubric),
                'icon' => $rubric->icon
            ];
        });

        $data = $menuItems->render();
        /** @noinspection SqlResolve */
        $cache->set($cacheKey, $data, null, new DbDependency(['sql' => 'SELECT MAX(modified_at) FROM ' . ProductRubric::tableName()]));

        return $data;
    }

    /**
     * Gets the structure of the brand menu
     */
    public function getBrandMenuStructure() {
        $module = $this;
        $brands = ProductBrand::find()->orderBy('name')->all();
        $menuItems = array_map(function ($brand) use ($module) {
            /** @var ProductBrand $brand */
            return [
                'label' => $brand->name,
                'url' => $module->getBrandUri($brand),
            ];
        }, $brands);

        return $menuItems;
    }

    /**
     * @param \common\modules\catalog\models\Product $product
     * @param string $tagName
     *
     * @return bool
     */
    public function productHasTag(Product $product, string $tagName): bool {
        return !empty($product->tags[$tagName]);
    }

    /**
     * Get thambnail uri of product by image name
     * @param string $imageName
     * @param string $thumbName
     * @return string
     * @throws InvalidConfigException
     */
    public function getProductThumbnailUri(string $imageName, string $thumbName) {
        // TODO Need speedup
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        /** @var Image $entity */
        $entity = $filesManager->getEntityInstance('products/images' . '/' . $thumbName);
        $entity->fileName = $imageName;

        if (!$entity->exists(true)) {
            $entity = $filesManager->getEntityInstance('defaults');
        }

        return $entity->getUri(false, true);
    }
    
    /**
     * @param      $imageName
     * @param bool $allowDefault
     *
     * @return null|string
     * @throws \yii\base\InvalidConfigException
     */
    public function getProductImageUri($imageName, $allowDefault = true) {
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        /** @var Image $entity */
        $entity = $filesManager->getEntityInstance('products/images');
        $entity->fileName = $imageName;

        if (!$entity->exists($allowDefault)) {
            if ($allowDefault) {
                $entity = $filesManager->getEntityInstance('defaults');
    
            } else {
                return null;
            }
        }

        return $entity->getUri(false, true);
    }

    /**
     * Get thambnail path of product by image name
     * @param string $imageName
     * @param string $thumbName
     * @return string
     * @throws InvalidConfigException
     */
    public function getProductThumbnailPath(string $imageName, string $thumbName) {
        // TODO Need speedup
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        /** @var Image $entity */
        $entity = $filesManager->getEntityInstance('products/images' . '/' . $thumbName);
        $entity->fileName = $imageName;

        if (!$entity->exists(true)) {
            $entity = $filesManager->getEntityInstance('defaults');
        }

        return $entity->getFilePath(true);
    }/** @noinspection PhpUndefinedClassInspection */

    /**
     * Update future price
     * @param Product $product
     * @param float $value The value of future price
     * @param string $activeFrom The date which of price is actual
     * @param string $domain Domain alias
     * @return bool|ProductPrice
     * @throws \Exception
     * @throws \Throwable
     */
    public function updateFuturePrice($product, $value, $activeFrom = null, $domain = null) {
        if (is_null($domain)) {
            $domain = \Yii::$app->params['domain'];
        }

        $futurePrice = ProductPrice::find()
            ->where(['product_id' => $product->id])
            ->andWhere(['domain_name' => $domain])
            ->onlyFuture()->one();

        try {
            if (!$futurePrice) {
                $futurePrice = new ProductPrice();
                $futurePrice->product_id = $product->id;
                $futurePrice->domain_name = $domain;
                $futurePrice->status = 'active';
            }

            if ($futurePrice->value == $value && $futurePrice->active_from === $activeFrom) {
                return $futurePrice;
            }

            $futurePrice->value = $value;
            $futurePrice->active_from = $activeFrom;

            if (!$futurePrice->isFuture()) {
                $product->addError('futurePrice', 'The price date must be future.');
                // TODO Do not shows errors for multiinput field
                $futurePrice->addError('active_from', 'The price date must be future.');
                return $futurePrice;
            }

            if (false === $futurePrice->save()) {
                $error = 'An error occurred while setting a new price: "' . implode(' ', $futurePrice->getFirstErrors()) . '".';
                $product->addError('futurePrice', $error);
                $futurePrice->addError('value', $error);
                return $futurePrice;
            }
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
            $error = 'Unknown error occurred while setting a new price.';
            $product->addError('futurePrice', $error);
            $futurePrice->addError('value', $error);
            return $futurePrice;
        }

        return $futurePrice;
    }

    /**
     * Insert new price
     * @param Product $product
     * @param float $value The value of new price
     * @param string|null $domain Domain name
     * @return bool|ProductPrice
     * @throws \yii\db\Exception
     */
    public function insertNewPrice($product, $value, $domain = null) {
        if (is_null($domain)) {
            $domain = \Yii::$app->params['domain'];
        }

        /** @var ProductPrice $price */
        $price = ProductPrice::find()
            ->where(['product_id' => $product->id])
            ->andWhere(['domain_name' => $domain])
            ->onlyActive()->one();

        if ($price && $price->value == $value) {
            // Nothing to change
            return $price;
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if ($price) {
                $price->status = 'inactive';
                $price->save();
            }

            $price = new ProductPrice();
            $price->product_id = $product->id;
            $price->domain_name = $domain;
            $price->value = $value;
            $price->status = 'active';

            if (false === $price->save()) {
                $price->addError('value', 'An error occurred while setting a new price: "' . implode(' ', $price->getFirstErrors()) . '".');
                return $price;
            }

            $transaction->commit();
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
            $price->addError('price', 'Unknown error occurred while setting a new price: "' . $exception->getMessage() . '".');
            $transaction->rollBack();
            return $price;
        }

        return $price;
    }

    /**
     * Get thumbnail path of the brand by image name
     *
     * @param string $imageName
     * @param string $thumbName Such as: little
     * @return string
     * @throws InvalidConfigException
     */
    public function getBrandThumbnailPath($imageName, string $thumbName = 'little') {
        // TODO Need speedup
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        /** @var Image $entity */
        $entity = $filesManager->getEntityInstance('brands/images' . '/' . $thumbName);
        $entity->fileName = (string)$imageName;

        if (!$entity->exists()) {
            $entity = $filesManager->getEntityInstance('defaults');
            $entity->fileName = 'brand-logo.png';
        }

        return $entity->getFilename();
    }

    /**
     * Recalculate the quantity of products in each rubric
     *
     * @return true;
     */
    public function recalculateQuantity() {
        try {
            Yii::$app->getDb()->createCommand('UPDATE product_rubric' .
                ' LEFT JOIN (' .
                'SELECT parent.id, COUNT(product.name) as product_quantity' .
                ' FROM product_rubric AS node, product_rubric AS parent, product' .
                ' LEFT JOIN product2product_rubric as rlink ON rlink.product_id = product.id' .
                ' WHERE node.left_key BETWEEN parent.left_key AND parent.right_key AND node.id = rlink.rubric_id' .
                ' GROUP BY parent.id' .
                ') calculated' .
                ' ON calculated.id = product_rubric.id' .
                ' SET product_rubric.product_quantity = calculated.product_quantity')->execute();
        } catch (\Exception $exception) {
            Yii::debug('Failed to recalculate the quantity of products in each rubric.');
            return false;
        }

        return true;
    }

    /**
     * Get thambnail uri of brand by image name
     *
     * @param string $imageName
     * @param string $thumbName Such as: little
     * @return string
     * @throws InvalidConfigException
     */
    public function getBrandThumbnailUri($imageName, string $thumbName = 'little') {
        // TODO Need speedup
        /** @var \common\modules\files\Module $filesManager */
        $filesManager = Yii::$app->getModule('files');
        /** @var Image $entity */
        $entity = $filesManager->getEntityInstance('brands/images' . '/' . $thumbName);
        $entity->fileName = (string)$imageName;

        if (!$entity->exists()) {
            $entity = $filesManager->getEntityInstance('defaults');
            $entity->fileName = 'brand-logo.png';
        }

        return $entity->getUri();
    }
}
