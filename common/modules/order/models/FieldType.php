<?php
namespace common\modules\order\models;

use yii;

class FieldType extends \common\base\models\BaseActiveRecord
{
    public static function tableName()
    {
        return '{{%order_field_type}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['widget'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => yii::t('order', 'ID'),
            'name' => yii::t('order', 'Name'),
            'widget' => yii::t('order', 'Widget'),
        ];
    }
}
