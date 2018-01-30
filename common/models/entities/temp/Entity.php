<?php

namespace common\models\entities;

use Yii;
use \yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/** @noinspection PropertiesInspection */

/**
 * This is the model class for table "entity".
 *
 * @property int $entity_id
 * @property string $entity_name
 * @property int $entity_type_id
 * @property string $entity_desc
 * @property string $entity_created
 * @property string $entity_updated
 * @property int $entity_is_disabled
 * @property int $entity_is_deleted
 * @property string $entity_deleted_at
 * @property int $entity_author_user_id
 *
 * @property User $entityAuthorUser
 * @property EntityType $entityType
 * @property EntityTree[] $entityTrees
 */
class Entity extends ActiveRecord
{

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'entity_created',
                'updatedAtAttribute' => 'entity_updated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param string $name
     * @param int|null $user_id
     * @return static
     */
    public static function create(string $name, int $user_id = null) {
        $entity = new static();
        $entity->entity_name = $name;
        $entity->entity_author_user_id = is_null($user_id)
            ? yii::$app->user->id
            : $user_id;

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'entity';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['entity_name', 'entity_author_user_id'], 'required'],
            [['entity_type_id', 'entity_is_disabled', 'entity_is_deleted', 'entity_author_user_id'], 'integer'],
            [['entity_desc'], 'string'],
//            [['entity_created', 'entity_updated', 'entity_deleted_at'], 'safe'],
            [['entity_name'], 'string', 'max' => 150],
            [['entity_author_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['entity_author_user_id' => 'id']],
            [['entity_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EntityType::className(), 'targetAttribute' => ['entity_type_id' => 'entity_type_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity_id' => Yii::t('entity', 'Entity ID'),
            'entity_name' => Yii::t('entity', 'Entity Name'),
            'entity_type_id' => Yii::t('entity', 'Entity Type ID'),
            'entity_desc' => Yii::t('entity', 'Entity Desc'),
            'entity_created' => Yii::t('entity', 'Entity Created'),
            'entity_updated' => Yii::t('entity', 'Entity Updated'),
            'entity_is_disabled' => Yii::t('entity', 'Entity Is Disabled'),
            'entity_is_deleted' => Yii::t('entity', 'Entity Is Deleted'),
            'entity_deleted_at' => Yii::t('entity', 'Entity Deleted At'),
            'entity_author_user_id' => Yii::t('entity', 'Entity Author User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityAuthorUser()
    {
        return $this->hasOne(User::className(), ['id' => 'entity_author_user_id'])->inverseOf('entities');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityType()
    {
        return $this->hasOne(EntityType::className(), ['entity_type_id' => 'entity_type_id'])->inverseOf('entities');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityTrees()
    {
        return $this->hasMany(EntityTree::className(), ['entity_tree_entity_id' => 'entity_id'])->inverseOf('entityTreeEntity');
    }

    /**
     * @inheritdoc
     * @return EntityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EntityQuery(get_called_class());
    }
}
