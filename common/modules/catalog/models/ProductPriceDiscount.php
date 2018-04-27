<?php

namespace common\modules\catalog\models;

use common\models\entities\User;
use common\modules\catalog\models\queries\ProductPriceDiscountQuery;
use Yii;
use common\base\models\BaseActiveRecord;

/**
 * This is the model class for table "product_price_discount".
 *
 * @property int        $id
 * @property string     $name
 * @property string     $title
 * @property string     $created_at
 * @property int        $author_id
 * @property string     $active_from
 * @property string     $active_to
 * @property int        $status
 * @property int        $value
 * @property float      $weight
 * @property int        $product_tag_id
 *
 * @property \common\models\entities\User       $author
 * @property bool       $isActive
 * @property \common\modules\catalog\models\ProductTag $productTag
 */
class ProductPriceDiscount extends BaseActiveRecord
{
    const STATUS = [
        'DISABLED' => 0, // Выключена, но показывается в админке
        'ACTIVE' => 1, // Активная скидка
        'DELETED' => 3, //Удалена
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS['DISABLED']],

            [['name', 'author_id'], 'required'],
            [['created_at', 'active_from', 'active_to'], 'safe'],
            [['author_id', 'status', 'product_tag_id', 'value', 'weight'], 'integer'],
            [['weight'], 'number'],
            [['name', 'title'], 'string', 'max' => 155],
            [['name'], 'unique'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author_id' => 'id']],
            [['product_tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductTag::class, 'targetAttribute' => ['product_tag_id' => 'id']],
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
            'created_at' => Yii::t('app', 'Created At'),
            'author_id' => Yii::t('app', 'Author ID'),
            'active_from' => Yii::t('app', 'Active From'),
            'active_to' => Yii::t('app', 'Active To'),
            'status' => Yii::t('app', 'Status'),
            'value' => Yii::t('app', 'Value'),
            'weight' => Yii::t('app', 'Weight'),
            'product_tag_id' => Yii::t('app', 'Product Tag ID'),
        ];
    }

    public function getIsActive() {
        return $this->status == self::STATUS['ACTIVE']
            && $this->active_from < time()
            && (is_null($this->active_to) || $this->active_to > time());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id'])->inverseOf('productPriceDiscounts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductTag()
    {
        return $this->hasOne(ProductTag::class, ['id' => 'product_tag_id'])->inverseOf('productPriceDiscounts');
    }

    /**
     * @inheritdoc
     * @return \common\modules\catalog\models\queries\ProductPriceDiscountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductPriceDiscountQuery(get_called_class());
    }
}
