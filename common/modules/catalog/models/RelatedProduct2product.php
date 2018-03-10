<?php

namespace common\modules\catalog\models;

use common\base\models\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "related_product2product".
 *
 * @property int $parent_product_id
 * @property int $related_product_id
 *
 * @property Product $parentProduct
 * @property Product $relatedProduct
 */
class RelatedProduct2product extends BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'related_product2product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_product_id', 'related_product_id'], 'required'],
            [['parent_product_id', 'related_product_id'], 'integer'],
            [['parent_product_id', 'related_product_id'], 'unique', 'targetAttribute' => ['parent_product_id', 'related_product_id']],
            [['related_product_id', 'parent_product_id'], 'unique', 'targetAttribute' => ['related_product_id', 'parent_product_id']],
            [['parent_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['parent_product_id' => 'id']],
            [['related_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['related_product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent_product_id' => Yii::t('app', 'Parent Product ID'),
            'related_product_id' => Yii::t('app', 'Related Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'parent_product_id'])->inverseOf('relatedProduct2products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'related_product_id'])->inverseOf('relatedProduct2products0');
    }

    /**
     * @inheritdoc
     * @return RelatedProduct2productQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RelatedProduct2productQuery(get_called_class());
    }
}
