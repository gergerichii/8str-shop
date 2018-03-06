<?php

namespace common\modules\catalog\models;

use common\base\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "product_tag2product".
 *
 * @property int $product_tag_id
 * @property int $product_id
 *
 * @property Product $product
 * @property ProductTag $productTag
 */
class ProductTag2product extends BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_tag2product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_tag_id', 'product_id'], 'required'],
            [['product_tag_id', 'product_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['product_tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductTag::className(), 'targetAttribute' => ['product_tag_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_tag_id' => Yii::t('app', 'Product Tag ID'),
            'product_id' => Yii::t('app', 'Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->inverseOf('productTag2products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTag()
    {
        return $this->hasOne(ProductTag::className(), ['id' => 'product_tag_id'])->inverseOf('productTag2products');
    }

    /**
     * @inheritdoc
     * @return ProductTag2productQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductTag2productQuery(get_called_class());
    }
}
