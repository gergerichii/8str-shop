<?php

namespace common\modules\catalog\models\forms;

use common\modules\catalog\models\Product;
use yii\base\Model;

/**
 * Class ProductImagesForm
 *
 * @property Product $product
 */
class ProductImagesForm extends Model
{
    /**
     * @var integer $id Product id
     */
    public $id;

    /**
     * @var Product|null $_product Product object
     */
    private $_product = null;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'required'],
            ['id', 'integer']
        ];
    }

    /**
     * Get images
     * @return string[]
     */
    public function getImages() {
        return $this->product ? $this->product->images : [];
    }

    /**
     * Get product
     * @return Product|null
     */
    public function getProduct() {
        if (is_null($this->_product)) {
            $this->_product = Product::find()->filterWhere(['id' => $this->id])->one();
        }

        return $this->_product;
    }

    /**
     * Set product object
     * @param Product $product
     */
    public function setProduct(Product $product) {
        $this->_product = $product;
    }
}