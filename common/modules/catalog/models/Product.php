<?php

namespace common\modules\catalog\models;

use common\helpers\ProductHelper;
use common\models\entities\User;
use common\modules\cart\interfaces\CartElement;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\AttributeBehavior;
use yii\caching\TagDependency;
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
 * @property ProductTag[] $tags
 * @property ProductPrice[] $prices
 * @property RelatedProduct2product[] $relatedProduct2productsParent
 * @property RelatedProduct2product[] $relatedProduct2productsChild
 * @property Product[] $relatedProducts
 * @property Product2productRubric[] $product2rubrics
 * @property Product[] $parentProducts
 * @property int $old_id        [INT(10)]
 * @property int $old_rubric_id [INT(10)]
 *
 * TODO Replace to business logic or form model
 * @property string $listOfRubrics Used for the form of editing.
 * @property array $tagCollection Used for the form of editing.
 * @property array $fieldForFuturePrice Field for future price with specific structure. Must be at most one future price. Used for the form of editing. ```['future' => ['value'=>0.0, 'active_from'=>Y-m-d H:i:s]]```
 * @property ProductPrice|null $futurePrice Future price
 * @property ProductPrice|null $price
 * @property ProductPrice|null $oldPrice
 * @property ProductPrice[] $frontendPrices
 * @property float|null $priceValue
 *
 * TODO: Добавить основную рубрику
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
     * The indexes used for prices array
     */
    const PRICE_INDEX_FOR_ACTUAL = 0;
    const PRICE_INDEX_FOR_OLD = 1;

    /**
     * @var array
     */
    private $_files = self::DEFAULT_FILES_JSON_STRUCTURE;

    /**
     * @var string|null $_listOfRubric List of rubrics
     */
    private $_listOfRubric = null;

    /**
     * @var array|null $_tagCollection Collection of tag
     */
    private $_tagCollection = null;

    /**
     * @var array|null $_fieldForFuturePrice Actual prices
     */
    private $_fieldForFuturePrice = null;

    /**
     * Price value
     * @var float|null $_price
     */
    private $_price;

    /** ------------------------------------ Настройка внутренней структуры ---------------------------------------- */
    /**
     * @inheritdoc
     */

    public function behaviors() {
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

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        return array(
            'default' => [
                '!id', 'name', 'title', 'desc', 'status', 'count',
                'show_on_home', 'on_list_top', 'market_upload', '!files',
                'delivery_time', 'created_at', 'modified_at', 'creator_id',
                'main_rubric_id',
                'listOfRubrics', // Uses for a custom field
                'tagCollection', // Uses for a custom field
                'fieldForFuturePrice', //  Uses for a custom field
                'priceValue', // Uses for a custom field
                'modifier_id', 'product_type_id', 'brand_id',
                'old_id', 'old_rubric_id'  //TODO: Удалить, когда запустится сайт
            ],
        );
    }

    /**
     * @inheritdoc
     */
    public function rules() {
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
            [['listOfRubrics', 'tagCollection', 'fieldForFuturePrice'], 'safe'],
            [['priceValue'], 'number', 'min' => 0],

            [['old_rubric_id', 'old_id'], 'integer'], //TODO: Удалить, когда запустится сайт
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            /**
             * @todo Add translation opportunity
             * 'id' => Yii::t('app', 'ID'),
             * 'name' => Yii::t('app', 'Name'),
             * 'title' => Yii::t('app', 'Title'),
             * 'desc' => Yii::t('app', 'Desc'),
             * 'status' => Yii::t('app', 'Status'),
             * 'count' => Yii::t('app', 'Count'),
             * 'show_on_home' => Yii::t('app', 'Show On Home'),
             * 'on_list_top' => Yii::t('app', 'On List Top'),
             * 'market_upload' => Yii::t('app', 'Market Upload'),
             * 'files' => Yii::t('app', 'Files'),
             * 'delivery_time' => Yii::t('app', 'Delivery Time'),
             * 'created_at' => Yii::t('app', 'Created At'),
             * 'modified_at' => Yii::t('app', 'Modified At'),
             * 'creator_id' => Yii::t('app', 'Creator ID'),
             * 'modifier_id' => Yii::t('app', 'Modifier ID'),
             * 'product_type_id' => Yii::t('app', 'Product Type ID'),
             * 'brand_id' => Yii::t('app', 'Brand ID'),
             * 'main_rubric_id' => Yii::t('app', 'Main Rubric ID'),
             *
             */
            'id' => 'ID',
            'name' => 'Name',
            'title' => 'Title',
            'desc' => 'Desc',
            'status' => 'Status',
            'count' => 'Count',
            'show_on_home' => 'Show On Home',
            'on_list_top' => 'On List Top',
            'market_upload' => 'Market Upload',
            'files' => 'Files',
            'delivery_time' => 'Delivery Time',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'creator_id' => 'Creator ID',
            'modifier_id' => 'Modifier ID',
            'product_type_id' => 'Product Type ID',
            'brand_id' => 'Brand ID',
            'main_rubric_id' => 'Main Rubric ID',
            'listOfRubrics' => 'List of rubrics',
            'tagCollection' => 'Tag collection',
            'fieldForFuturePrice' => 'Field for future price',
            'brandName' => 'Brand name',
            'priceValue' => 'Price value'
        ];
    }

    /**
     * @return string[]
     */
    public function getFiles(): array {
        return $this->_files;
    }

    /**
     * Set files
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
     * Add file
     * @param string $file
     * @return Product
     */
    public function addFile(string $file) {
        $part = ProductHelper::getFileStorePart($file);
        if (!in_array($file, $this->_files[$part])){
            $this->_files[$part][] = $file;
        }

        return $this;
    }

    /**
     * Delete file
     * @param string $file
     * @return $this
     */
    public function deleteFile(string $file) {
        $part = ProductHelper::getFileStorePart($file);
        if (false !== ($index = array_search($file, $this->_files[$part]))) {
            unset($this->_files[$part][$index]);
        }

        return $this;
    }

    /**
     * Get main image
     * @return string
     */
    public function getMainImage() {
        if ($this->images) {
            return current($this->images);
        }

        return '';
    }

    /**
     * Get images
     * @return string[]
     */
    public function getImages() {
        return (!empty($this->_files['images'])) ? $this->_files['images'] : [];
    }

    /**
     * Desolate images
     */
    public function desolateImages() {
        $this->_files['images'] = [];
    }

    /**
     * Get default files as JSON
     * @return string
     */
    public function getDefaultFilesJson() {
        return json_encode([
            'images' => [],
            'files' => [],
        ]);
    }

    public function afterFind() {
        $this->_prepareFiles();
        parent::afterFind();
    }

    public function afterRefresh() {
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
    public function getBrand() {
        return $this->hasOne(ProductBrand::className(), ['id' => 'brand_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator() {
        return $this->hasOne(User::className(), ['id' => 'creator_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier() {
        return $this->hasOne(User::className(), ['id' => 'modifier_id'])->inverseOf('products0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(ProductType::className(), ['id' => 'product_type_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct2Rubrics() {
        return $this->hasMany(Product2productRubric::className(), ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRubrics() {
        return $this->hasMany(ProductRubric::className(), ['id' => 'rubric_id'])->viaTable('product2product_rubric', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainRubric() {
        return $this->hasOne(ProductRubric::className(), ['id' => 'main_rubric_id']);
    }

    /**
     * @return ActiveQuery|ProductPriceQuery
     */
    public function getPrices() {
        return $this->hasMany(ProductPrice::className(), ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct2productsParent() {
        return $this->hasMany(RelatedProduct2product::className(), ['parent_product_id' => 'id'])->inverseOf('parentProduct');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct2productsChild() {
        return $this->hasMany(RelatedProduct2product::className(), ['related_product_id' => 'id'])->inverseOf('relatedProduct');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProducts() {
        return $this->hasMany(Product::className(), ['id' => 'related_product_id'])->viaTable('related_product2product', ['parent_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentProducts() {
        return $this->hasMany(Product::className(), ['id' => 'parent_product_id'])->viaTable('related_product2product', ['related_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags2products() {
        return $this->hasMany(ProductTag2product::className(), ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags() {
        return $this->hasMany(ProductTag::className(), ['id' => 'product_tag_id'])
            ->viaTable('product_tag2product', ['product_id' => 'id'])
            ->indexBy('name');
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find() {
        return new ProductQuery(get_called_class());
    }

    /* Выпилить отсюда создав бихэйвор */

    /**
     * Get cart id
     * @return int
     */
    public function getCartId() {
        return $this->id;
    }

    /**
     * Get cart name
     * @return string
     */
    public function getCartName() {
        return $this->name;
    }

    /**
     * Get cart price
     * @return int|string
     */
    public function getCartPrice() {
        /** @var \common\modules\catalog\Module $catalog */
        $catalog = yii::$app->getModule('catalog');
        return $catalog->priceOf($this, false);
    }

    /**
     * Get cart options
     * @return array
     */
    public function getCartOptions() {
        return [];
    }

    /**
     * Get list of rubrics
     * @return string ```'rubricId1,rubricId2,rubricId3'```
     */
    public function getListOfRubrics() {
        if (is_null($this->_listOfRubric) && $this->rubrics) {
            $this->_listOfRubric = array_reduce($this->rubrics, function ($carry, $item) {
                $id = $item->id;
                return $carry ? $carry . ',' . $id : $id;
            });
        }

        return $this->_listOfRubric;
    }

    /**
     * Set list of rubrics
     * @param string $listOfRubric List of rubrics
     */
    public function setListOfRubrics($listOfRubric) {
        $this->_listOfRubric = $listOfRubric;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        // TODO Replace at business logic or form model
        if (isset($this->listOfRubrics)) {
            $rubricIds = array_filter(explode(',', $this->listOfRubrics));
            $this->updateLinksWithRubrics($rubricIds);
        }

        if (isset($this->tagCollection)) {
            $this->updateLinksWithTags($this->tagCollection);
        }

        // Insert a new price when there is no actual price or the price has a different value
        if (isset($this->_price) && (!$this->price || (float)$this->price->value !== (float)$this->_price)) {
            $this->insertNewPrice($this->_price);
        }

        if (isset($this->_fieldForFuturePrice)) {
            $this->updateFieldForFuturePrice($this->_fieldForFuturePrice);
        }

        // Update sphinx index
        /*$sphinxIndex = ProductSphinxIndex::find()->where(['id' => $this->id])->one();
        if (!$sphinxIndex) {
            $sphinxIndex = new ProductSphinxIndex();
            $sphinxIndex->setAttribute('id', $this->id);
        }

        $sphinxIndex->setAttribute('name', $this->name);
        $sphinxIndex->save();
        */
        
        TagDependency::invalidate(Yii::$app->getCache(), __CLASS__);
    }

    /**
     * Update links with rubrics
     * @param array $rubricIds Identifiers of rubrics
     * @throws \yii\db\Exception
     */
    public function updateLinksWithRubrics($rubricIds) {
        $rubricIds = array_filter($rubricIds);

        $transaction = Yii::$app->getDb()->beginTransaction();
        foreach ($this->rubrics as $rubric) {
            if (false === ($index = array_search($rubric->id, $rubricIds))) {
                $this->unlink('rubrics', $rubric, true);
            } else {
                unset($rubricIds[$index]);
            }
        }

        $this->linkRubrics($rubricIds);

        $transaction->commit();
    }

    /**
     * Link rubrics
     * @param array $rubricIds Identifiers of rubrics
     */
    public function linkRubrics($rubricIds) {
        if ($rubricIds) {
            $rublics = ProductRubric::find()->where(['in', 'id', $rubricIds])->all();
            foreach ($rublics as $rublic) {
                $this->link('rubrics', $rublic);
            }
        }
    }

    /**
     * Get tag collection
     * @return array
     */
    public function getTagCollection() {
        if (is_null($this->_tagCollection) && $this->tags) {
            $tagsKey = $tagsValue = array_keys($this->tags);
            $this->_tagCollection = array_combine($tagsKey, $tagsValue);
        }

        return $this->_tagCollection;
    }

    /**
     * Set tag collection
     * @param array $tagCollection
     */
    public function setTagCollection($tagCollection) {
        if (!$tagCollection) {
            $this->_tagCollection = [];
        } else {
            $this->_tagCollection = $tagCollection;
        }
    }

    /**
     * Update links with tags
     * @param array $tagCollection
     * @throws \yii\db\Exception
     */
    public function updateLinksWithTags($tagCollection) {
        $transaction = Yii::$app->getDb()->beginTransaction();
        foreach ($this->tags as $tag) {
            if (false === ($index = array_search($tag->name, $tagCollection))) {
                $this->unlink('tags', $tag, true);
            } else {
                unset($tagCollection[$index]);
            }
        }

        $this->linkTags($tagCollection);

        $transaction->commit();
    }

    /**
     * Link tags
     * @param array $tagCollection Tags
     */
    public function linkTags($tagCollection) {
        if ($tagCollection) {
            $tags = ProductTag::find()->where(['in', 'name', $tagCollection])->all();
            foreach ($tags as $tag) {
                $this->link('tags', $tag);
            }
        }
    }

    /**
     * Get field for future price
     * @return array|null
     */
    public function getFieldForFuturePrice() {
        if (is_null($this->_fieldForFuturePrice)) {
            $this->_fieldForFuturePrice = [];

            if ($this->futurePrice) {
                $this->_fieldForFuturePrice['future'] = [
                    'id' => $this->futurePrice->id,
                    'value' => $this->futurePrice->value,
                    'active_from' => $this->futurePrice->active_from
                ];
            } else {
                $this->_fieldForFuturePrice['future'] = [
                    'value' => 0.0,
                    'active_from' => ''
                ];
            }
        }

        return $this->_fieldForFuturePrice;
    }

    /**
     * Get price
     * @return ProductPriceQuery
     */
    public function getPrice() {
        /** @var ProductPriceQuery $query */
        $query = $this->hasOne(ProductPrice::className(), ['product_id' => 'id']);
        $query->onlyActive();
        $query->orderBy(['active_from' => SORT_DESC]);
        $query->inverseOf('product');
        return $query;
    }

    /**
     * Get old price
     * @return ProductPriceQuery
     */
    public function getOldPrice() {
        /** @var ProductPriceQuery $query */
        $query = $this->hasOne(ProductPrice::className(), ['product_id' => 'id']);
        $query->onlyInactive();
        $query->orderBy(['active_from' => SORT_DESC]);
        $query->inverseOf('product');
        return $query;
    }

    /**
     * Get future price
     * @return ProductPriceQuery
     */
    public function getFuturePrice() {
        /** @var ProductPriceQuery $query */
        $query = $this->hasOne(ProductPrice::className(), ['product_id' => 'id']);
        $query->onlyFuture();
        $query->inverseOf('product');
        return $query;
    }

    /**
     * Set field for future price
     * @param $fieldForFuturePrice
     */
    public function setFieldForFuturePrice($fieldForFuturePrice) {
        $this->_fieldForFuturePrice = $fieldForFuturePrice;
    }

    /**
     * Insert new price
     * @param float $value The value of new price
     * @param string|null $domain Domain name
     * @return bool
     * @throws \yii\db\Exception
     */
    public function insertNewPrice($value, $domain = null) {
        if ($this->price && $this->price->value == $value) {
            // Nothing to change
            return true;
        }

        if (is_null($domain)) {
            $domain = \Yii::$app->params['domains'][\Yii::$app->params['domain']];
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            if ($this->price) {
                $this->price->status = 'inactive';
                $this->price->save();
            }

            $price = new ProductPrice();
            $price->product_id = $this->id;
            $price->domain_name = $domain;
            $price->value = $value;
            $price->status = 'active';

            if (false === $price->save()) {
                $this->addError('price', 'An error occurred while setting a new price: "' . implode(' ', $price->getFirstErrors()) . '".');
                return false;
            }

            $transaction->commit();
        } catch (ErrorException $exception) {
            $this->addError('price', 'An error occurred while setting a new price: "' . $exception->getMessage() . '".');
            $transaction->rollBack();
            return false;
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
            $this->addError('price', 'Unknown error occurred while setting a new price: "' . $exception->getMessage() . '".');
            $transaction->rollBack();
            return false;
        }

        return true;
    }

    /**
     * Update future price
     * @param float $value The value of future price
     * @param string $activeFrom The date which of price is actual
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function updateFuturePrice($value, $activeFrom = null) {
        if ($value == 0) {
            return false;
        }

        try {
            if ($this->futurePrice) {
                $price = $this->futurePrice;
            } else {
                $price = new ProductPrice();
                $price->product_id = $this->id;
                $price->domain_name = \Yii::$app->params['domains'][\Yii::$app->params['domain']];
                $price->status = 'active';
                $this->populateRelation('futurePrice', $price);
            }

            if ($price->value == $value && $price->active_from === $activeFrom) {
                return false;
            }

            $price->value = $value;
            $price->active_from = $activeFrom;

            if (!$price->isFuture()) {
                $this->addError('futurePrice', 'The price date must be future.');
                // TODO Do not shows errors for multiinput field
                $price->addError('futurePrice', 'The price date must be future.');
                return false;
            }

            if (false === $price->save()) {
                $this->addError('futurePrice', 'An error occurred while setting a new price: "' . implode(' ', $price->getFirstErrors()) . '".');
                return false;
            }
        } catch (ErrorException $exception) {
            $this->addError('futurePrice', 'An error occurred while setting a new price: "' . $exception->getMessage() . '".');
            return false;
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage());
            $this->addError('futurePrice', 'Unknown error occurred while setting a new price.');
            return false;
        }

        return true;
    }

    /**
     * Update actual prices
     * @param array $fieldForFuturePrice
     * @throws \Exception
     * @throws \Throwable
     */
    public function updateFieldForFuturePrice($fieldForFuturePrice) {
        if (array_key_exists('future', $fieldForFuturePrice)) {
            $this->updateFuturePrice($fieldForFuturePrice['future']['value'], $fieldForFuturePrice['future']['active_from']);
        }
    }

    /**
     * Get brand name
     * @return string
     */
    public function getBrandName() {
        return $this->brand ? $this->brand->name : null;
    }

    /**
     * Get rubric name
     * @return string
     */
    public function getRubricName() {
        return $this->rubrics ? current($this->rubrics)->name : null;
    }

    /**
     * Get price value
     * @return float|null
     */
    public function getPriceValue() {
        if (is_null($this->_price)) {
            $this->_price = $this->price ? (float)$this->price->value : null;
        }

        return $this->_price;
    }

    /**
     * Set value of price
     * @param float $value
     * @return void
     */
    public function setPriceValue($value) {
        $this->_price = $value;
    }
}
