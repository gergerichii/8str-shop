<?php

namespace common\modules\catalog\models;

use common\base\CacheTags;
use common\base\models\nestedSets\NSActiveRecord;
use common\modules\catalog\models\queries\ProductRubricQuery;
use corpsepk\yml\behaviors\YmlCategoryBehavior;
use kartik\tree\models\TreeTrait;
use yii\behaviors\TimestampBehavior;
use yii\caching\ChainedDependency;
use yii\caching\DbDependency;
use yii\caching\DbQueryDependency;
use yii\caching\TagDependency;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

//use common\modules\treeManager\models\TreeTrait;

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
 * @property int $visible_on_home_page Whether to show rubrics that are hidden on the home page
 * @property string $created_at
 * @property string $modified_at
 * @property int $product_quantity
 *
 * @property string $icon
 *
 * @property Product2productRubric[] $product2productRubrics
 * @property Product[] $products
 * @property ProductPriceDiscount[] $productPriceDiscounts
 * @property int                    $root          [int(11)]
 * @property int                    $icon_type     [smallint(1)]
 * @property bool                   $active        [tinyint(1)]
 * @property bool                   $selected      [tinyint(1)]
 * @property bool                   $disabled      [tinyint(1)]
 * @property bool                   $readonly      [tinyint(1)]
 * @property bool                   $visible       [tinyint(1)]
 * @property bool                   $collapsed     [tinyint(1)]
 * @property bool                   $movable_u     [tinyint(1)]
 * @property bool                   $movable_d     [tinyint(1)]
 * @property bool                   $movable_l     [tinyint(1)]
 * @property bool                   $movable_r     [tinyint(1)]
 * @property bool                   $removable     [tinyint(1)]
 * @property bool                   $removable_all [tinyint(1)]
 * @property int                    $old_id        [int(11)]
 * @property int                    $old_parent_id [int(11)]
 *
 * TODO: Добавить связи getParents и getChildren
 */
class ProductRubric extends NSActiveRecord
{
    use TreeTrait {
        isDisabled as parentIsDisabled;
        find as treeFind;
    }

    /**
     * @var string the classname for the TreeQuery that implements the NestedSetQueryBehavior.
     * If not set this will default to `kartik	ree\models\TreeQuery`.
     */
    public static $treeQueryClass; // change if you need to set your own TreeQuery

    /**
     * @var bool whether to HTML encode the tree node names. Defaults to `true`.
     */
    public $encodeNodeNames = true;

    /**
     * @var bool whether to HTML purify the tree node icon content before saving.
     * Defaults to `true`.
     */
    public $purifyNodeIcons = true;

    /**
     * @var array activation errors for the node
     */
    public $nodeActivationErrors = [];

    /**
     * @var array node removal errors
     */
    public $nodeRemovalErrors = [];

    /**
     * @var bool attribute to cache the `active` state before a model update. Defaults to `true`.
     */
    public $activeOrig = true;

    /**
     * @var string
     */
    public $treeBehaviorName = [];

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
            [['tree', 'level', 'left_key', 'right_key', 'visible_on_home_page'], 'integer'],
            [['desc'], 'string'],
            [['name', 'icon'], 'string', 'max' => 150],
            [['title'], 'string', 'max' => 255],
            [['material_path'], 'string', 'max' => 500],
            [['tree', 'left_key', 'right_key', 'level'], 'unique', 'targetAttribute' => ['tree', 'left_key', 'right_key', 'level']],
            [['old_id', 'old_parent_id'], 'integer'] //TODO: Закоментировать когда не нужно
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'modified_at',
            'value' => new Expression('NOW()'),
        ];
        
        /** @var self[] $ymlModels */
        static $ymlModels = [];
        $behaviors['ymlCategory'] = [
            'class' => YmlCategoryBehavior::class,
            'scope' => function ($model) use (&$ymlModels) {
                /** @var \common\modules\catalog\models\queries\ProductQuery $model */
                $ymlModels = $model->select(['id', 'name', 'material_path'])->where(['active' => 1])->indexBy('material_path')->all();
            },
            'dataClosure' => function ($model) use (&$ymlModels) {
                /** @var self $model */
                $parentId = null;
                if ($model->material_path) {
                    $parentPath = preg_replace('#(?:(.+)/)?[^/]+$#', '\1', $model->material_path);
                    if(isset($ymlModels[$parentPath])) {
                        $parentId = $ymlModels[$parentPath]->id;
                    }
                }
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'parentId' => $parentId,
                ];
            }
        ];

        return $behaviors;
    }
    
    /**
     * @return \common\base\models\nestedSets\NSActiveQuery|\common\modules\catalog\models\queries\ProductRubricQuery
     */
    public static function find()
    {
        return new ProductRubricQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            /*'id' => Yii::t('ar.rubric', 'ID'),
            'tree' => Yii::t('ar.rubric', 'Tree'),
            'level' => Yii::t('ar.rubric', 'Level'),
            'left_key' => Yii::t('ar.rubric', 'Left Key'),
            'right_key' => Yii::t('ar.rubric', 'Right Key'),
            'name' => Yii::t('ar.rubric', 'Name'),
            'title' => Yii::t('ar.rubric', 'Title'),
            'desc' => Yii::t('ar.rubric', 'Desc'),
            'material_path' => Yii::t('ar.rubric', 'Material path'),*/
            'id' => 'ID',
            'tree' => 'Tree',
            'level' => 'Level',
            'left_key' => 'Left Key',
            'right_key' => 'Right Key',
            'name' => 'Name',
            'title' => 'Title',
            'desc' => 'Desc',
            'icon' => 'Icon',
            'material_path' => 'Material path',
            'visible_on_home_page' => 'Visible on the home page'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct2productRubrics()
    {
        return $this->hasMany(Product2productRubric::class, ['rubric_id' => 'id'])->inverseOf('rubric');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable('product2product_rubric', ['rubric_id' => 'id']);
    }
    
    /**
     * @return array
     * @throws \Throwable
     */
    public static function getProductsCounts() {
        static $ret;
        
        if (empty($ret)) {
            $dependency = new ChainedDependency([
                'dependencies' => [
                    new DbDependency([
                        'sql' => \Yii::$app->db->createCommand(
                            "SELECT `UPDATE_TIME` FROM `INFORMATION_SCHEMA`.`PARTITIONS` WHERE `TABLE_SCHEMA` = 'tima_shop' AND `TABLE_NAME` = 'product'"
                        )->sql
                    ]),
                    new DbDependency([
                        'sql' => \Yii::$app->db->createCommand(
                            "SELECT `UPDATE_TIME` FROM `INFORMATION_SCHEMA`.`PARTITIONS` WHERE `TABLE_SCHEMA` = 'tima_shop' AND `TABLE_NAME` = 'product_rubric'"
                        )->sql
                    ]),
                    new DbDependency([
                        'sql' => \Yii::$app->db->createCommand(
                            "SELECT `UPDATE_TIME` FROM `INFORMATION_SCHEMA`.`PARTITIONS` WHERE `TABLE_SCHEMA` = 'tima_shop' AND `TABLE_NAME` = 'product2product_rubric'"
                        )->sql
                    ]),
                    new TagDependency(['tags' => [
                        CacheTags::CATALOG, CacheTags::PRODUCTS, CacheTags::RUBRICS,
                    ]]),
                ]
            ]);
            
            /** @TODO: отрефакторить под активрекорд или PDO */
            $ret = \Yii::$app->db->cache(function($db){
                /** @var \yii\db\Connection $db */
                return $db->createCommand("
                    SELECT `pr`.`id`,
                        (
                            SELECT DISTINCT count(`prpz`.`id`) FROM `product_rubric` `prpz`
                                LEFT JOIN `product2product_rubric` `p2pr` ON `prpz`.`id` = `p2pr`.`rubric_id`
                                LEFT JOIN `product` `p` ON `prpz`.`id` = `p`.`main_rubric_id`
                            WHERE `prpz`.`left_key` >= `pr`.`left_key` AND `prpz`.`right_key` <= `pr`.`right_key` AND `p`.`id` IS NOT NULL AND `p`.`status` = 1
                    
                        ) AS `count`
                    FROM `product_rubric` `pr`
                ")->queryAll();
            }, 0, $dependency);
            $ret = ArrayHelper::map($ret, 'id', 'count');
        }
        
        return $ret;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductPriceDiscounts()
    {
        return $this->hasMany(ProductPriceDiscount::class, ['product_rubric_id' => 'id'])->inverseOf('productRubric');
    }

    /**
     * Note overriding isDisabled method is slightly different when
     * using the trait. It uses the alias.
     */
    public function isDisabled()
    {
        // TODO AccessControl or checking Role?
        /*if (Yii::$app->user->username !== 'admin') {
            return true;
        }*/

        return $this->parentIsDisabled();
    }

    /**
     * To string
     * @return string
     */
    public function __toString () {
        return (isset($this->title)) ? $this->title : $this->name;
    }
}
