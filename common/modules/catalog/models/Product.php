<?php

namespace common\modules\catalog\models;

use common\helpers\ProductHelper;
use common\models\entities\User;
use common\modules\cart\interfaces\CartElement;
use common\modules\catalog\Module;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\AttributeBehavior;
use \yii\db\ActiveQuery;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "product".
 *
 * ---------Атрибуты
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $desc
 * @property int $status
 * @property int $count
 * @property bool $show_on_home
 * @property bool $on_list_top
 * @property bool $market_upload
 * @property string $files            [json]
 * @property int $delivery_time
 * @property string $created_at
 * @property string $modified_at
 * @property int $creator_id
 * @property int $modifier_id
 * @property int $product_type_id
 * @property int $brand_id
 * @property int $main_rubric_id
 *
 * ----Магические свойства
 *
 * @property string $mainImage
 * @property string[] $images
 * @property string $defaultFilesJson [json]
 *
 * ----------Связи
 *
 * @property ProductBrand $brand
 * @property User $creator
 * @property User $modifier
 * @property ProductType $type

 * @property ProductRubric[] $rubrics
 * @property ProductRubric $mainRubric
 * @property Product2productRubric[] $tags2products
 * @property ProductRubric[] $tags
 * @property ProductPrice[] $prices
 * @property RelatedProduct2product[] $relatedProduct2productsParent
 * @property RelatedProduct2product[] $relatedProduct2productsChild
 * @property Product[] $relatedProducts
 * @property Product2productRubric[] $product2rubrics
 * @property Product[] $parentProducts
 * @property int $old_id        [INT(10)]
 * @property int $old_rubric_id [INT(10)]
 *
 *  TODO: Добавить основную рубрику
 *
 * TODO: Добавить сеттер для прайса и скидки с автоматической генерацией соответствующих записей в базе
 *
 * TODO: Всемто имплемента, переделать на бихэйвор который будет накладываться на модель модулем Cart, исходя из настроек
 */
class Product extends ActiveRecord implements CartElement
{

    /**
     * Статусы продуктов
     */
    const STATUS = [
        'DELETED' => 0, //Остается в виде истории, и можно увидеть только в базе напрямую
        'ACTIVE' => 1, // Виден на сайте
        'HIDDEN' => 2, //Не виден ни кому кроме админа
    ];

    /**
     * Тип продукта по умолчанию
     */
    const DEFAULT_PRODUCT_TYPE_ID = 1;

    const DEFAULT_FILES_JSON_STRUCTURE = [
        'images' => [],
        'files' => [],
    ];

    /**
     * @var array
     */
    private $_files = self::DEFAULT_FILES_JSON_STRUCTURE;

    /** ------------------------------------ Настройка внутрренней структуры ---------------------------------------- */
    /**
     * @inheritdoc
     */

    public function behaviors(){
        return [
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'modifier_id',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'files',
                    ActiveRecord::EVENT_BEFORE_INSERT => 'files',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'files',
                ],
                'value' => function () {
                    return json_encode($this->_files);
                },
            ],
       ];
    }

    public static function tableName()
    {
        return 'product';
    }


    public function scenarios()
    {
        return array(
            'default' => [
                '!id', 'name', 'title', 'desc', 'status', 'count',
                'show_on_home', 'on_list_top', 'market_upload', '!files',
                'delivery_time', 'created_at', 'modified_at', 'creator_id',
                'modifier_id', 'product_type_id', 'brand_id',
                'old_id', 'old_rubric_id'  //TODO: Удалить, когда запустится сайт
            ],
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creator_id', 'modifier_id'], 'default', 'value' => Yii::$app->user->id],
            [['files'], 'default', 'value' => $this->getDefaultFilesJson()],
            [['product_type_id'], 'default', 'value' => self::DEFAULT_PRODUCT_TYPE_ID],
            [['status'], 'default', 'value' => self::STATUS['HIDDEN']],
            [['name'], 'trim'],

            [['name', 'ext_attributes', 'files', '1c_data', 'creator_id', 'modifier_id'], 'required'],
            [['desc'], 'string'],
            [['status', 'count', 'delivery_time', 'show_on_home', 'on_list_top', 'market_upload',
              'creator_id', 'modifier_id', 'product_type_id', 'brand_id', 'main_rubric_id'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['title'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['modifier_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['modifier_id' => 'id']],
            [['product_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductType::className(), 'targetAttribute' => ['product_type_id' => 'id']],
            [['main_rubric_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductRubric::className(), 'targetAttribute' => ['main_rubric_id' => 'id']],

            [['old_rubric_id', 'old_id'], 'integer'], //TODO: Удалить, когда запустится сайт
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
            'count' => Yii::t('app', 'Count'),
            'show_on_home' => Yii::t('app', 'Show On Home'),
            'on_list_top' => Yii::t('app', 'On List Top'),
            'market_upload' => Yii::t('app', 'Market Upload'),
            'files' => Yii::t('app', 'Files'),
            'delivery_time' => Yii::t('app', 'Delivery Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'creator_id' => Yii::t('app', 'Creator ID'),
            'modifier_id' => Yii::t('app', 'Modifier ID'),
            'product_type_id' => Yii::t('app', 'Product Type ID'),
            'brand_id' => Yii::t('app', 'Brand ID'),
            'main_rubric_id' => Yii::t('app', 'Main Rubric ID'),
        ];
    }

    /**
     * @return string[]
     */
    public function getFiles() : array {
        return $this->_files;
    }

    /**
     * @param string[] $files
     * @return Product
     * @throws ErrorException
     */
    public function setFiles(array $files) {
        $tmpFiles = [];
        foreach ($files as $key => $file) {
            if (!is_string($file)) {
                throw new ErrorException('Только строка допускается в качестве имени файла');
            }
            $part = ProductHelper::getFileStorePart($file);
            $tmpFiles[$part][$key] = $file;
        }
        $this->_files = $tmpFiles;

        return $this;
    }

    /**
     * @param string $file
     * @return Product
     */
    public function addFile(string $file) {
        $part = ProductHelper::getFileStorePart($file);
        if (!in_array($file, $this->_files[$part]))
            $this->_files[$part][] = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getMainImage() {
        return (!empty($this->_files['images'][0])) ? $this->_files['images'][0] : '';
    }

    /**
     * @return string[]
     */
    public function getImages() {
        return (!empty($this->_files['images'])) ? $this->_files['images'] : [];
    }

    public function getDefaultFilesJson() {
        return json_encode([
            'images' => [],
            'files' => [],
        ]);
    }

    public function afterFind(){
        $this->_prepareFiles();
        parent::afterFind();
    }

    public function afterRefresh(){
        $this->_prepareFiles();
        parent::afterRefresh();
    }

    /** ----------------------------------------- Сервисные методы -------------------------------------------------- */

    /**
     * TODO: Посмотреть, как можно сделать это лучше
     */
    private function _prepareFiles() {
        $this->_files = json_decode($this->getAttribute('files'), true);
    }

    /** ---------------------------------------  Магические методы -------------------------------------------------- */

    /**
     * @return string
     */
    public function __toString() {
        return (isset($this->title)) ? $this->title : $this->name;
    }

    /** ---------------------------------------- Связи и связанные таблицы -------------------------------------------*/


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand(){
        return $this->hasOne(ProductBrand::className(), ['id' => 'brand_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator(){
        return $this->hasOne(User::className(), ['id' => 'creator_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier(){
        return $this->hasOne(User::className(), ['id' => 'modifier_id'])->inverseOf('products0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType(){
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct2Rubrics(){
        return $this->hasMany(Product2productRubric::className(), ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRubrics(){
        return $this->hasMany(ProductRubric::className(), ['id' => 'rubric_id'])->viaTable('product2product_rubric', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainRubric(){
        return $this->hasOne(ProductRubric::className(), ['id' => 'main_rubric_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPrices(){
        return $this->hasMany(ProductPrice::className(), ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct2productsParent()
    {
        return $this->hasMany(RelatedProduct2product::className(), ['parent_product_id' => 'id'])->inverseOf('parentProduct');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct2productsChild()
    {
        return $this->hasMany(RelatedProduct2product::className(), ['related_product_id' => 'id'])->inverseOf('relatedProduct');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'related_product_id'])->viaTable('related_product2product', ['parent_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'parent_product_id'])->viaTable('related_product2product', ['related_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags2products()
    {
        return $this->hasMany(ProductTag2product::className(), ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(ProductTag::className(), ['id' => 'product_tag_id'])
            ->viaTable('product_tag2product', ['product_id' => 'id'])
            ->indexBy('name');
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }
    
    /* Выпилить отсюда создав бихэйвор */
    
    public
    function getCartId() {
        return $this->id;
    }
    
    public
    function getCartName() {
        return $this->name;
    }
    
    public
    function getCartPrice() {
        /** @var \common\modules\catalog\Module $catalog */
        $catalog = yii::$app->getModule('catalog');
        return $catalog->priceOf($this, false);
    }
    
    public
    function getCartOptions() {
        return [];
    }
}
