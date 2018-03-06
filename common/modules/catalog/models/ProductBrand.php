<?php

namespace common\modules\catalog\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use common\base\models\BaseActiveRecord;

/**
 * This is the model class for table "product_brand".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $desc
 * @property string $logo
 *
 * @property Product[] $products
 * @property ProductPriceDiscount[] $productPriceDiscounts
 */
class ProductBrand extends BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product_brand}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'alias' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'alias',
                'ensureUnique' => true
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'alias'], 'required'],
            [['desc', 'alias'], 'string'],
            [['name', 'logo', 'alias'], 'string', 'max' => 150],
            [['name'], 'unique'],
            [['old_id'], 'integer'], //TODO: Закоментировать, когда не нужно
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', 'Alias'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'logo' => Yii::t('app', 'Logo'),
        ];
    }

    /**
     * Convert object to string
     *
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * Get products
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasMany(Product::className(), ['brand_id' => 'id'])->inverseOf('brand');
    }

    /**
     * Get discounts on the price of the products
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceDiscounts() {
        return $this->hasMany(ProductPriceDiscount::className(), ['product_brand_id' => 'id'])->inverseOf('productBrand');
    }

    /**
     * Get query object of the brand
     *
     * @inheritdoc
     *
     * @return ProductBrandQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProductBrandQuery(get_called_class());
    }
}
