<?php

namespace common\modules\catalog\models\forms;

use common\modules\catalog\models\Product;
use common\modules\catalog\models\ProductPrice;
use common\modules\catalog\models\queries\ProductPriceQuery;
use common\modules\catalog\CatalogModule;
use common\traits\ErrorsForMultipleInput;
use yii\base\Model;

/**
 * Class ProductPricesForm
 *
 * @property ProductPrice[]|array $prices
 * @property ProductPrice[]|array $futurePrices
 * @property Product $product
 */
class ProductPricesForm extends Model
{
    use ErrorsForMultipleInput;

    /**
     * Product id
     *
     * @var int $id
     */
    public $id;

    /**
     * @var Product|null $virtualProduct Product object
     */
    private $virtualProduct = null;

    /**
     * Virtual property for prices
     *
     * @var null|ProductPrice[]|array $virtualPrices
     */
    private $virtualPrices = null;

    /**
     * Virtual property for future prices
     * @var null|ProductPrice[]|array $virtualFuturePrices
     */
    private $virtualFuturePrices = null;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'integer'],
            [['prices', 'futurePrices'], 'safe'],
        ];
    }

    /**
     * Save prices
     */
    public function save() {
        $db = \Yii::$app->getDb();
        $transaction = $db->beginTransaction();
        if (isset($this->virtualPrices)) {
            $this->savePrices();
        }

        if (isset($this->virtualFuturePrices)) {
            $this->saveFuturePrices();
        }

        if ($this->hasErrors()) {
            $transaction->rollBack();
        } else {
            $transaction->commit();
        }
    }

    /**
     * Save prices
     *
     * @throws \yii\db\Exception
     */
    private function savePrices() {
        /** @var CatalogModule $catalog */
        $catalog = \Yii::$app->getModule('catalog');

        foreach ($this->virtualPrices as $index => &$priceData) {
            $price = $catalog->insertNewPrice($this->product, $priceData['value'], $priceData['domain_name']);
            if ($price->hasErrors()) {
                $this->addMultipleError('prices', $index, $priceData, $price);
            }

            $priceData = $price;
        }
    }

    /**
     * Save future prices
     *
     * @throws \Exception
     * @throws \Throwable
     */
    private function saveFuturePrices() {
        /** @var CatalogModule $catalog */
        $catalog = \Yii::$app->getModule('catalog');

        foreach ($this->virtualFuturePrices as $index => &$priceData) {
            $price = $catalog->updateFuturePrice($this->product, $priceData['value'], $priceData['active_from'], $priceData['domain_name']);

            if ($price->hasErrors()) {
                $this->addMultipleError('futurePrices', $index, $priceData, $price);
            }

            $priceData = $price;
        }
    }

    /**
     * Set prices
     * @param array $pricesData
     */
    public function setPrices($pricesData) {
        $this->virtualPrices = $pricesData;
    }

    /**
     * Set future prices
     * @param array $futurePricesData
     */
    public function setFuturePrices($futurePricesData) {
        $this->virtualFuturePrices = $futurePricesData;
    }

    /**
     * Get prices
     * @return array|ProductPrice[]
     */
    public function getPrices() {
        if (isset($this->virtualPrices)) {
            return $this->virtualPrices;
        }

        /** @var ProductPriceQuery $query */
        $query = ProductPrice::find();
        $prices = $query->where(['product_id' => $this->id])
            ->onlyActive()
            ->indexBy('domain_name')
            ->all();

        $domainsPrices = [];
        foreach (\Yii::$app->params['domains'] as $domainAlias => $domainName) {
            if (array_key_exists($domainAlias, $prices)) {
                $price = $prices[$domainAlias];
            } else {
                $price = new ProductPrice();
                $price->setAttributes([
                    'domain_name' => $domainAlias,
                ]);
            }

            $domainsPrices[] = $price;
        }

        $this->virtualPrices = $domainsPrices;

        return $domainsPrices;
    }

    /**
     * Get future prices
     * @return array|ProductPrice[]
     */
    public function getFuturePrices() {
        if (isset($this->virtualFuturePrices)) {
            return $this->virtualFuturePrices;
        }

        /** @var ProductPriceQuery $query */
        $query = ProductPrice::find();
        $prices = $query->where(['product_id' => $this->id])
            ->onlyFuture()
            ->indexBy('domain_name')
            ->all();

        $domainsPrices = [];
        foreach (\Yii::$app->params['domains'] as $domainAlias => $domainName) {
            if (array_key_exists($domainAlias, $prices)) {
                $price = $prices[$domainAlias];
            } else {
                $price = new ProductPrice();
                $price->setAttributes([
                    'domain_name' => $domainAlias,
                ]);
            }

            $domainsPrices[] = $price;
        }

        $this->virtualFuturePrices = $domainsPrices;

        return $domainsPrices;
    }

    /**
     * Get product
     * @return Product|null
     */
    public function getProduct() {
        if (is_null($this->virtualProduct)) {
            $this->virtualProduct = Product::find()->filterWhere(['id' => $this->id])->one();
        }

        return $this->virtualProduct;
    }

    /**
     * Set product object
     * @param Product $product
     */
    public function setProduct(Product $product) {
        $this->virtualProduct = $product;
    }
}