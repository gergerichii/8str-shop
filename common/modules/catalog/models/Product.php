<?php

namespace common\modules\catalog\models;

use common\behaviors\JsonBehaviour;
use common\helpers\ProductHelper;
use common\models\entities\User;
use common\modules\cart\interfaces\CartElement;
use common\modules\catalog\models\queries\ProductQuery;
use common\modules\catalog\CatalogModule;
use corpsepk\yml\behaviors\YmlOfferBehavior;
use common\modules\catalog\models\YmlOffer as Offer;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\base\models\BaseActiveRecord;
use yii\caching\TagDependency;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * ---------Атрибуты
 *
 * @property int                      $id
 * @property string                   $name
 * @property string                   $title
 * @property string                   $desc
 * @property int                      $status
 * @property int                      $count
 * @property bool                     $show_on_home
 * @property bool                     $on_list_top
 * @property bool                     $market_upload
 * @property string                   $files               [json]
 * @property int                      $delivery_time
 * @property string                   $created_at
 * @property string                   $modified_at
 * @property int                      $creator_id
 * @property int                      $modifier_id
 * @property int                      $product_type_id
 * @property int                      $brand_id
 * @property int                      $main_rubric_id
 *
 * ----Магические свойства
 *
 * @property string                   $mainImage
 * @property string[]                 $images
 * @property string                   $defaultFilesJson    [json]
 *
 * ----------Связи
 *
 * @property ProductBrand             $brand
 * @property User                     $creator
 * @property User                     $modifier
 * @property ProductType              $type
 * @property ProductRubric[]          $rubrics
 * @property ProductRubric            $mainRubric
 * @property Product2productRubric[]  $tags2products
 * @property ProductTag[]             $tags
 * @property ProductPrice[]           $prices
 * @property RelatedProduct2product[] $relatedProduct2productsParent
 * @property RelatedProduct2product[] $relatedProduct2productsChild
 * @property Product[]                $relatedProducts
 * @property Product2productRubric[]  $product2rubrics
 * @property Product[]                $parentProducts
 * @property int                      $old_id              [INT(10)]
 * @property int                      $old_rubric_id       [INT(10)]
 *
 * TODO Replace to business logic or form model
 * @property string                   $listOfRubrics       Used for the form of editing.
 * @property array                    $tagCollection       Used for the form of editing.
 * @property array                    $fieldForFuturePrice Field for future price with specific structure. Must be at most one future price. Used for the form of editing. ```['future' => ['value'=>0.0, 'active_from'=>Y-m-d H:i:s]]```
 * @property ProductPrice|null        $futurePrice         Future price
 * @property ProductPrice|null        $price
 * @property ProductPrice|null        $oldPrice
 * @property string                   $cartName
 * @property int                      $cartId
 * @property \yii\db\ActiveQuery      $product2Rubrics
 * @property string                   $brandName
 * @property string                   $rubricName
 * @property array                    $cartOptions
 * @property string|int               $cartPrice
 * @property float|null               $priceValue
 * @property string                   $model       [varchar(150)]
 * @property string                   $vendor_code [varchar(150)]
 * @property string                   $barcode     [varchar(150)]
 * @property string                   $warranty    [varchar(30)]
 * @property int                      $delivery_days [varchar(5)]
 * @property string                   $short_desc    [varchar(500)]
 * @property string                   $tech_desc
 *
 * TODO: Добавить сеттер для прайса и скидки с автоматической генерацией соответствующих записей в базе
 *
 * TODO: Всемто имплемента, переделать на бихэйвор который будет накладываться на модель модулем Cart, исходя из настроек
 */
class Product extends BaseActiveRecord implements CartElement
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
    
    const DEFAULT_ADD_FIELDS_JSON_STRUCTURE = [
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
    private $_files = self::DEFAULT_ADD_FIELDS_JSON_STRUCTURE;

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
            'json' => [
                'class' => JsonBehaviour::class,
            ],
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'modifier_id',
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => 'files',
                    ActiveRecord::EVENT_BEFORE_INSERT => 'files',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'files',
                ],
                'value' => function () {
                    return json_encode($this->_files);
                },
            ],
            'ymlOffer' => [
                'class' => YmlOfferBehavior::class,
                'scope' => function ($model) {
                    /** @var ProductQuery $model */
                    $model->active()->with('price', 'oldPrice', 'brand.country', 'mainRubric', 'rubrics', 'tags')
                        ->where(['market_upload' => 1])->limit(10000);
                },
                'dataClosure' => function ($model) {
                    /** @var \common\modules\catalog\CatalogModule $catalog */
                    static $catalog;
                    if (!$catalog) {
                        $catalog = Yii::$app->getModule('catalog');
                    }
                    /** @var self $model */
                    $url = $catalog->getCatalogUri(null, $model);
                    $url = Yii::$app->urlManager->createAbsoluteUrl($url);
                    
                    $price = $catalog->priceOf($model, false);
                    $oldPrice = $catalog->oldPriceOf($model, false);
                    $oldPrice <= $price and $oldPrice = null;
                    
                    return new Offer([
                        'id' => $model->id,
                        'type' => 'vendor.model',
                        'available' => $model->count > 0,
                        'url' => $url,
                        'price' => $price,
                        'oldprice' => $oldPrice,
                        'currencyId' => 'RUR',
                        'categoryId' => $model->main_rubric_id,
                        'picture' => ArrayHelper::map($model->images, 'id', function ($image) use ($catalog) {
                            return $catalog->getProductImageUri($image, false);
                        }),
                        'name' => $model->name,
                        'vendor' => !empty($model->brand) ? $model->brand->name : null,
                        'description' => (!empty($model->short_desc)) ? strip_tags($model->short_desc) : mb_substr(strip_tags($model->desc), 0, 500) . '...',
                        'vendorCode' => !empty($model->vendor_code) ? $model->vendor_code : null,
                        'barcode' => !empty($model->barcode) ? $model->barcode : null,
                        'model' => !empty($model->model) ? $model->model : null,
                        'delivery' => true,
                        'local_delivery_cost' => 350,
                        'store' => true,
                        'pickup' => true,
                        'typePrefix' => isset($model->productType) ? $model->productType->name : null,
                        'seller_warranty' => isset($model->warranty) ? $model->warranty : null,
                        'country_of_origin' => isset($model->brand->country) ? $model->brand->country->name : null,
                        'deliveryOptions' => [
                            [
                                'tag_name' => 'option', 'cost' => 350, 'days' => $model->delivery_days,
                            ]
                        ]
                    ]);
                }
            ],
            /*'product_quantity' => [
                'class' => ProductQuantityBehavior::class,
            ]*/
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
                '!id', 'name', 'title', 'desc', 'short_desc', 'tech_desc', 'status', 'count',
                'show_on_home', 'on_list_top', 'market_upload', '!files',
                'delivery_time', 'delivery_days', 'created_at', 'modified_at', 'creator_id',
                'main_rubric_id', 'model', 'vendor_code', 'barcode', 'warranty',
                'listOfRubrics', // Uses for a custom field
                'tagCollection', // Uses for a custom field
                'fieldForFuturePrice', //  Uses for a custom field
                'priceValue', // Uses for a custom field
                'modifier_id', 'product_type_id', 'brand_id',
                'old_id', 'old_rubric_id'  //TODO: Удалить, когда запустится сайт
            ],
            'oldbase' => [
                '!id', 'name', 'title', 'desc', 'short_desc', 'tech_desc', 'status', 'count',
                'show_on_home', 'on_list_top', 'market_upload', '!files',
                'delivery_time', 'delivery_days', 'created_at', 'modified_at', 'creator_id',
                'main_rubric_id', 'warranty',
                'listOfRubrics', // Uses for a custom field
                'tagCollection', // Uses for a custom field
                'fieldForFuturePrice', //  Uses for a custom field
                'priceValue', // Uses for a custom field
                'modifier_id', 'product_type_id', 'brand_id',
                'old_id', 'old_rubric_id'  //TODO: Удалить, когда запустится сайт
            ],
            'search' => [
                'id', 'name', 'title', 'desc', 'tech_desc', 'status', 'count',
                'show_on_home', 'on_list_top', 'market_upload', 'files',
                'delivery_time', 'delivery_days', 'created_at', 'modified_at', 'creator_id',
                'main_rubric_id', 'warranty',
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
            [['id'], 'integer'],
            [['creator_id', 'modifier_id'], 'default', 'value' => Yii::$app->user->id, 'except' => 'search'],
            [['files'], 'default', 'value' => $this->getDefaultFilesJson(), 'except' => 'search'],
            [['product_type_id'], 'default', 'value' => self::DEFAULT_PRODUCT_TYPE_ID, 'except' => 'search'],
            [['status'], 'default', 'value' => self::STATUS['ACTIVE'], 'except' => 'search'],
            ['warranty', 'default', 'value' => '1 год', 'except' => 'search'],
            [['name'], 'trim'],

            [['name', 'ext_attributes', 'files', '1c_data', 'creator_id', 'modifier_id', 'model'], 'required', 'except' => 'search'],
            [['desc', 'tech_desc', 'model', 'vendor_code', 'barcode', 'warranty'], 'string'],
            [['short_desc'], 'string', 'max' => 500],
            [['status', 'count', 'delivery_time', 'show_on_home', 'on_list_top', 'market_upload',
                'creator_id', 'modifier_id', 'product_type_id', 'brand_id', 'main_rubric_id'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['delivery_days'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 150],
            [['title'], 'string', 'max' => 255],
            [['name'], 'unique', 'except' => 'search'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductBrand::class, 'targetAttribute' => ['brand_id' => 'id'], 'except' => 'search'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id'], 'except' => 'search'],
            [['modifier_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modifier_id' => 'id'], 'except' => 'search'],
            [['product_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductType::class, 'targetAttribute' => ['product_type_id' => 'id'], 'except' => 'search'],
            [['main_rubric_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductRubric::class, 'targetAttribute' => ['main_rubric_id' => 'id'], 'except' => 'search'],
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
            'name' => 'Название',
            'title' => 'Title',
            'desc' => 'Описание',
            'tech_desc' => 'Технические характеристики',
            'short_desc' => 'Краткое описание',
            'model' => 'Модель',
            'status' => 'Статус',
            'count' => 'Количество',
            'show_on_home' => 'Показывать на главной',
            'on_list_top' => 'Вверху списка',
            'market_upload' => 'Выгружать на маркет',
            'files' => 'Файлы',
            'delivery_time' => 'Срок доставки',
            'delivery_days' => 'Срок доставки (яндекс)',
            'created_at' => 'Создан',
            'modified_at' => 'Отредактирован',
            'creator_id' => 'Создатель',
            'modifier_id' => 'Редактор',
            'product_type_id' => 'Тип продукта',
            'brand_id' => 'Производитель',
            'main_rubric_id' => 'Основная рубрика',
            'listOfRubrics' => 'Список рубрик',
            'tagCollection' => 'Список меток',
            'fieldForFuturePrice' => 'Field for future price',
            'brandName' => 'Имя брэнда',
            'priceValue' => 'Цена',
            'vendor_code' => 'Код производителя',
            'barcode' => 'Штрихкод',
            'warranty' => 'Гарантия',
            'old_id' => 'id на старом сайте',
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
     * @return bool
     */
    public function addFile(string $file) {
        $part = ProductHelper::getFileStorePart($file);
        if ($ret = !in_array($file, $this->_files[$part])){
            $this->_files[$part][] = $file;
        }

        return $ret;
    }

    /**
     * Delete file
     * @param string $file
     * @return bool
     */
    public function deleteFile(string $file) {
        $part = ProductHelper::getFileStorePart($file);
        $ret = false;
        if (false !== ($index = array_search($file, $this->_files[$part]))) {
            unset($this->_files[$part][$index]);
            $this->_files[$part] = array_values($this->_files[$part]);
            $ret = true;
        }

        return $ret;
    }
    
    /**
     * Checks for a file
     *
     * @param $file
     *
     * @return false|int|string
     */
    public function hasFile($file) {
        $part = ProductHelper::getFileStorePart($file);
        return array_search($file, $this->_files[$part]);
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
        if (is_array($this->getAttribute('files'))) {
            $this->_files = $this->getAttribute('files');
        } else {
            $this->_files = json_decode($this->getAttribute('files'), true);
        }
    }

    /** ---------------------------------------  Магические методы -------------------------------------------------- */

    /**
     * @return string
     */
    public function __toString() {
        return (!empty($this->title)) ? $this->title : $this->name;
    }

    /** ---------------------------------------- Связи и связанные таблицы -------------------------------------------*/


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand() {
        return $this->hasOne(ProductBrand::class, ['id' => 'brand_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator() {
        return $this->hasOne(User::class, ['id' => 'creator_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier() {
        return $this->hasOne(User::class, ['id' => 'modifier_id'])->inverseOf('products0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType() {
        return $this->hasOne(ProductType::class, ['id' => 'product_type_id'])->inverseOf('products');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct2Rubrics() {
        return $this->hasMany(Product2productRubric::class, ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRubrics() {
        return $this->hasMany(ProductRubric::class, ['id' => 'rubric_id'])->viaTable('product2product_rubric', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainRubric() {
        return $this->hasOne(ProductRubric::class, ['id' => 'main_rubric_id']);
    }

    /**
     * @return ActiveQuery|\common\modules\catalog\models\queries\ProductPriceQuery
     */
    public function getPrices() {
        return $this->hasMany(ProductPrice::class, ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct2productsParent() {
        return $this->hasMany(RelatedProduct2product::class, ['parent_product_id' => 'id'])->inverseOf('parentProduct');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProduct2productsChild() {
        return $this->hasMany(RelatedProduct2product::class, ['related_product_id' => 'id'])->inverseOf('relatedProduct');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedProducts() {
        return $this->hasMany(Product::class, ['id' => 'related_product_id'])->viaTable('related_product2product', ['parent_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentProducts() {
        return $this->hasMany(Product::class, ['id' => 'parent_product_id'])->viaTable('related_product2product', ['related_product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags2products() {
        return $this->hasMany(ProductTag2product::class, ['product_id' => 'id'])->inverseOf('product');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags() {
        return $this->hasMany(ProductTag::class, ['id' => 'product_tag_id'])
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
        /** @var \common\modules\catalog\CatalogModule $catalog */
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
     *
     * @param $insert
     * @param $changedAttributes
     *
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        // TODO Replace at business logic or form model
        /** @var CatalogModule $catalog */
        $catalog = Yii::$app->getModule('catalog');
        if (isset($this->listOfRubrics)) {
            $rubricIds = array_filter(explode(',', $this->listOfRubrics));
            $this->updateLinksWithRubrics($rubricIds);
        }

        if (isset($this->tagCollection)) {
            $this->updateLinksWithTags($this->tagCollection);
        }

        // Insert a new price when there is no actual price or the price has a different value
        if (isset($this->_price) && (!$this->price || (float)$this->price->value !== (float)$this->_price)) {
            $catalog->insertNewPrice($this,$this->_price);
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
     * @return \common\modules\catalog\models\queries\ProductPriceQuery
     */
    public function getPrice() {
        /** @var \common\modules\catalog\models\queries\ProductPriceQuery $query */
        $query = $this->hasOne(ProductPrice::class, ['product_id' => 'id']);
        $query->onlyActive();
        $query->orderBy(['active_from' => SORT_DESC]);
        $query->inverseOf('product');
        return $query;
    }

    /**
     * Get old price
     * @return \common\modules\catalog\models\queries\ProductPriceQuery
     */
    public function getOldPrice() {
        /** @var \common\modules\catalog\models\queries\ProductPriceQuery $query */
        $query = $this->hasOne(ProductPrice::class, ['product_id' => 'id']);
        $query->alias('oldPrice');
        $query->onlyInactive();
        $query->orderBy(['active_from' => SORT_DESC]);
        $query->inverseOf('product');
        return $query;
    }

    /**
     * Get future price
     * @return \common\modules\catalog\models\queries\ProductPriceQuery
     */
    public function getFuturePrice() {
        /** @var \common\modules\catalog\models\queries\ProductPriceQuery $query */
        $query = $this->hasOne(ProductPrice::class, ['product_id' => 'id']);
        $query->onlyFuture();
        $query->alias('futurePrice');
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
     * Update actual prices
     * @param array $fieldForFuturePrice
     * @throws \Exception
     * @throws \Throwable
     */
    public function updateFieldForFuturePrice($fieldForFuturePrice) {
        if (array_key_exists('future', $fieldForFuturePrice) && !empty(intval($fieldForFuturePrice['future']['value']))) {
            /** @var CatalogModule $catalog */
            $catalog = Yii::$app->getModule('catalog');
            $catalog->updateFuturePrice($this, $this->fieldForFuturePrice['future']['value'], $fieldForFuturePrice['future']['active_from']);
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
    
    /**
     * @param string|\common\modules\catalog\models\ProductTag $tag
     *
     * @return bool
     */
    public function hasTag($tag) {
        if ($this->isNewRecord) {
            $ret = false;
        } elseif (is_string($tag)) {
            $ret = (bool) ProductTag::find()
                ->joinWith('productTag2products as p')
                ->andWhere(['name' => $tag, 'p.product_id' => $this->id])
                ->count();
        } elseif ($tag instanceof ProductTag) {
            $ret = (bool) ProductTag2product::find()
                ->andWhere(['product_tag_id' => $tag->id, 'product_id' => $this->id])
                ->count();
        } else {
            $ret = false;
        }
        
        return $ret;
    }
}
