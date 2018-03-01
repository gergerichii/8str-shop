<?php

namespace common\modules\catalog\models;

use common\base\models\nestedSets\NSActiveRecord;
use common\modules\treeManager\models\TreeTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
 *
 * @property string $icon
 *
 * @property Product2productRubric[] $product2productRubrics
 * @property Product[] $products
 * @property ProductPriceDiscount[] $productPriceDiscounts
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
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'modified_at',
            'value' => new Expression('NOW()'),
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find();
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
