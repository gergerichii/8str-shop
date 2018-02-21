<?php

namespace common\modules\catalog\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\base\models\BaseActiveRecord;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_tag".
 *
 * @property int                    $id
 * @property string                 $name
 * @property string                 $title
 * @property string                 $desc
 * @property int                    $status
 * @property string                 $add_data
 * @property string                 $show_at
 * @property string                 $hide_at
 * @property string                 $image
 *
 * @property ProductPriceDiscount[] $productPriceDiscounts
 * @property array                  $defaultAddDataJson
 * @property \yii\db\ActiveQuery    $products
 * @property ProductTag2product[]   $productTag2products
 * @property bool                   $show_on_product [tinyint(1)]
 * @property bool                   $use_as_group    [tinyint(1)]
 */
class ProductTag extends BaseActiveRecord
{

    const STATUS = [
        'HIDDEN' => 0, //Используется для служебных целей. Не видна пользователям
        'SHOWED' => 1, //Отображается при выводе товара
    ];

    private $_data = [
        'showAt' => 0,
        'hideAt' => 0,
    ];

    public function behaviors(){
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'add_data',
                    ActiveRecord::EVENT_BEFORE_INSERT => 'add_data',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'add_data',
                ],
                'value' => function () {
                    return json_encode($this->_data);
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['add_data'], 'default', 'value' => $this->getDefaultAddDataJson()],
            [['show_on_product', 'use_as_group'], 'default', 'value' => false],
            [['status'], 'default', 'value' => self::STATUS['HIDDEN']],

            [['name', 'add_data', 'show_on_product', 'use_as_group'], 'required'],
            [['desc', 'add_data'], 'string'],
            [['status', 'show_on_product', 'use_as_group'], 'integer'],
            [['name', 'title'], 'string', 'max' => 150],
            [['image'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'title' => Yii::t('app', 'Title'),
            'desc' => Yii::t('app', 'Desc'),
            'status' => Yii::t('app', 'Status'),
            'add_data' => Yii::t('app', 'Add Data'),
            'image' => Yii::t('app', 'Image'),
            'show_on_product' => Yii::t('app', 'Show on product'),
            'use_as_group' => Yii::t('app', 'Use as Group'),
        ];
    }

    /**
     * @return array
     * TODO: Доработать
     */
    public function getDefaultAddDataJson() {
        return json_encode([
            'showAt' => time(),
            'hideAt' => 0,
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceDiscounts()
    {
        return $this->hasMany(ProductPriceDiscount::className(), ['product_tag_id' => 'id'])->inverseOf('productTag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTag2products()
    {
        return $this->hasMany(ProductTag2product::className(), ['product_tag_id' => 'id'])->inverseOf('productTag');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])
            ->viaTable('product_tag2product', ['product_tag_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ProductTagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductTagQuery(get_called_class());
    }
}
