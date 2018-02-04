<?php

namespace common\modules\catalog\models;

use common\helpers\CatalogHelper;
use common\models\entities\User;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "product_price".
 *
 * @property int $id
 * @property int $product_id
 * @property string $domain_name
 * @property int $author_id
 * @property string $active_from
 * @property string $value
 *
 * @property User $author
 * @property Product $product
 *
 */
class ProductPrice extends ActiveRecord implements ProductPriceInterface
{
    public function behaviors () {
        return [
            [
               'class' => BlameableBehavior::className(),
               'createdByAttribute' => 'author_id',
               'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active_from'], 'default', 'value' => new Expression('NOW()')],

            [['product_id', 'author_id'], 'integer'],
            [['product_id', 'domain_name', 'value'], 'required'],
            [['active_from'], 'safe'],
            [['value'], 'number'],
            [['domain_name'], 'string', 'max' => 150],
            [['domain_name', 'product_id', 'active_from'], 'unique', 'targetAttribute' => ['domain_name', 'product_id', 'active_from']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'domain_name' => Yii::t('app', 'Domain Name'),
            'author_id' => Yii::t('app', 'Author ID'),
            'active_from' => Yii::t('app', 'Active From'),
            'value' => Yii::t('app', 'Цена'),
        ];
    }

    /**
     * @return string
     *
     */
    public function __toString () {
        return Yii::$app->formatter->asDecimal($this->value) . ' р.';
    }

    public function getValue(){
        return $this->getAttribute('value');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id'])->inverseOf('productPrices');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id'])->inverseOf('productPrices');
    }

    /**
     * @inheritdoc
     * @return ProductPriceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductPriceQuery(get_called_class());
    }

    /**
     * @param bool $insert
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function beforeSave($insert){
         if ($this->isNewRecord) {
             return parent::beforeSave($insert);
         } else {
             throw new ErrorException('Price is read only. Create a new price record for update current product price');
         }
     }

}