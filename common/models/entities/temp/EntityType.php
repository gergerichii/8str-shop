<?php

namespace common\models\entities;

use Yii;
use \yii\db\ActiveRecord;

/** @noinspection PropertiesInspection */

/**
 * This is the model class for table "entity_type".
 *
 * @property int $entity_type_id
 * @property string $entity_type_name
 * @property string $entity_type_desc
 *
 * @property Entity[] $entities
 */
class EntityType extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'entity_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_type_name'], 'required'],
            [['entity_type_desc'], 'string'],
            [['entity_type_name'], 'string', 'max' => 150],
            [['entity_type_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity_type_id' => Yii::t('app', 'Entity Type ID'),
            'entity_type_name' => Yii::t('app', 'Entity Type Name'),
            'entity_type_desc' => Yii::t('app', 'Entity Type Desc'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntities()
    {
        return $this->hasMany(Entity::className(), ['entity_type_id' => 'entity_type_id'])->inverseOf('entityType');
    }
}
