<?php

namespace common\modules\counters\models;

use common\base\models\BaseActiveRecord;
use common\models\entities\User;
use common\models\entities\UserAddresses;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * This is the model class for table "counters".
 *
 * @property int $id
 * @property string $value
 * @property int $position
 * @property string $included_pages
 * @property string $includedPages
 * @property string $excluded_pages
 * @property string $excludedPages
 * @property int $created_at
 * @property int $createdAt
 * @property int $created_by
 * @property int $createdBy
 * @property int $modified_at
 * @property int $modifiedAt
 * @property int $modified_by
 *
 * @property User $creator
 * @property UserAddresses $modifier
 * @property string        $name [varchar(255)]
 * @property bool          $active [tinyint(1)]
 */
class Counters extends BaseActiveRecord
{
    
    public const POSITIONS = [
        View::POS_BEGIN => 'В начале страницы',
        View::POS_END => 'В конце страницы',
    ];

    /**
     * @return array
     */
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ],
        ]);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string'],
            ['name', 'unique'],
            ['name', 'required'],
            [['value'], 'required'],
            [['value', 'included_pages', 'excluded_pages'], 'string'],
            ['active', 'boolean'],
            ['active', 'default', 'value' => true],
            [['position'], 'integer'],
            [['position'], 'default', 'value' => View::POS_BEGIN],
            [['created_by'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['modified_by'], 'exist', 'skipOnError' => false, 'targetClass' => User::class, 'targetAttribute' => ['modified_by' => 'id']],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название счетчика',
            'value' => 'Код счетчика',
            'position' => 'Расположение на странице',
            'included_pages' => 'Показывать на страницах (Если не указать, будет на всех)',
            'excluded_pages' => 'Исключить страницы (Если не указать, будет на всех)',
            'active' => 'Активный',
            'created_at' => 'Создан',
            'created_by' => 'Создатель',
            'modified_at' => 'Изменен',
            'modified_by' => 'Редактор',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier()
    {
        return $this->hasOne(UserAddresses::class, ['id' => 'modified_by']);
    }

    /**
     * @inheritdoc
     * @return CountersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountersQuery(get_called_class());
    }
}
