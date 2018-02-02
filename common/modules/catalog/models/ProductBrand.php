<?php

namespace common\modules\catalog\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_brand".
 *
 * @property int $id
 * @property string $name
 * @property string $desc
 * @property string $logo
 *
 * @property Product[] $products
 * @property ProductPriceDiscount[] $productPriceDiscounts
 */
class ProductBrand extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['desc'], 'string'],
            [['name', 'logo'], 'string', 'max' => 150],
            [['name'], 'unique'],
            [['old_id'], 'integer'], //TODO: Закоментировать, когда не нужно
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'desc' => Yii::t('app', 'Desc'),
            'logo' => Yii::t('app', 'Logo'),
        ];
    }


    public function __toString(){
        return $this->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['brand_id' => 'id'])->inverseOf('brand');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceDiscounts()
    {
        return $this->hasMany(ProductPriceDiscount::className(), ['product_brand_id' => 'id'])->inverseOf('productBrand');
    }

    /**
     * @inheritdoc
     * @return ProductBrandQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductBrandQuery(get_called_class());
    }
}
