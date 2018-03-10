<?php

namespace common\modules\news\models;

use common\models\entities\User;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $introtext
 * @property string $fulltext
 * @property string $created_at
 * @property string $modified_at
 * @property string $published_at
 * @property int $creator_id
 * @property int $modifier_id
 * @property string $image
 * @property int $external_id
 *
 * @property User $creator
 * @property User $modifier
 */
class Article extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'modifier_id',
            ],
            'timestamps' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'modified_at',
                'value' => new Expression('NOW()'),
            ],
            'publish' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'published_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
            'alias' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'slugAttribute' => 'alias'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['introtext', 'fulltext', 'alias'], 'string'],
            [['created_at', 'modified_at', 'published_at'], 'safe'],
            [['creator_id', 'modifier_id', 'introtext', 'alias', 'title'], 'required'],
            [['creator_id', 'modifier_id', 'external_id'], 'integer'],
            [['title', 'alias'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 512],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['creator_id' => 'id']],
            [['modifier_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['modifier_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'alias' => 'Alias',
            'introtext' => 'Introtext',
            'fulltext' => 'Fulltext',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'published_at' => 'Published At',
            'creator_id' => 'Creator ID',
            'modifier_id' => 'Modifier ID',
            'image' => 'Image',
            'external_id' => 'External ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator() {
        return $this->hasOne(User::class, ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModifier() {
        return $this->hasOne(User::class, ['id' => 'modifier_id']);
    }
}
