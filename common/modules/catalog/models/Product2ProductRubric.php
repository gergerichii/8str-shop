<?php

namespace common\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "product2product_rubric".
 *
 * @property int $product_id
 * @property int $rubric_id
 *
 * @property Product $product
 * @property ProductRubric $rubric
 */
class Product2ProductRubric extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product2product_rubric';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'rubric_id'], 'required'],
            [['product_id', 'rubric_id'], 'integer'],
            [['product_id', 'rubric_id'], 'unique', 'targetAttribute' => ['product_id', 'rubric_id']],
            [['rubric_id', 'product_id'], 'unique', 'targetAttribute' => ['rubric_id', 'product_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['rubric_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductRubric::className(), 'targetAttribute' => ['rubric_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'rubric_id' => Yii::t('app', 'Rubric ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->inverseOf('product2ProductRubrics');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRubric()
    {
        return $this->hasOne(ProductRubric::className(), ['id' => 'rubric_id'])->inverseOf('product2ProductRubrics');
    }

    /**
     * @inheritdoc
     * @return Product2ProductRubricQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new Product2ProductRubricQuery(get_called_class());
    }
}