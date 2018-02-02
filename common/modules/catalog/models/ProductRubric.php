<?php

namespace common\modules\catalog\models;

use common\base\models\nestedSets\NSActiveRecord;
use Yii;

/**
 * This is the model class for table "product_rubric".
 *
 * @property int $id
 * @property int $tree
 * @property int $level
 * @property int $left_key
 * @property int $right_key
 * @property string $name
 * @property string $title
 * @property string $desc
 * @property string $material_path
 *
 * @property Product2productRubric[] $product2productRubrics
 * @property Product[] $products
 * @property ProductPriceDiscount[] $productPriceDiscounts
 *
 * TODO: Добавить связи getParents и getChildren
 */
class ProductRubric extends NSActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_rubric';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['tree', 'level', 'left_key', 'right_key', 'name'], 'required'],
            [['tree', 'level', 'left_key', 'right_key'], 'integer'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 150],
            [['title'], 'string', 'max' => 255],
            [[ 'material_path'], 'string', 'max' => 500],
            [['tree', 'left_key', 'right_key', 'level'], 'unique', 'targetAttribute' => ['tree', 'left_key', 'right_key', 'level']],
            [['old_id', 'old_parent_id'], 'integer'] //TODO: Закоментировать когда не нужно
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('ar.rubric', 'ID'),
            'tree' => Yii::t('ar.rubric', 'Tree'),
            'level' => Yii::t('ar.rubric', 'Level'),
            'left_key' => Yii::t('ar.rubric', 'Left Key'),
            'right_key' => Yii::t('ar.rubric', 'Right Key'),
            'name' => Yii::t('ar.rubric', 'Name'),
            'title' => Yii::t('ar.rubric', 'Title'),
            'desc' => Yii::t('ar.rubric', 'Desc'),
            'material_path' => Yii::t('ar.rubric', 'Material path'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct2productRubrics()
    {
        return $this->hasMany(Product2productRubric::className(), ['rubric_id' => 'id'])->inverseOf('rubric');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product2product_rubric', ['rubric_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceDiscounts()
    {
        return $this->hasMany(ProductPriceDiscount::className(), ['product_rubric_id' => 'id'])->inverseOf('productRubric');
    }

    public function __toString () {
        return (isset($this->title)) ? $this->title : $this->name;
    }
}
