<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.05.2018
 * Time: 17:21
 */

namespace common\behaviors;
use yii\base\Behavior;
use yii\base\DynamicModel;
use yii\db\ActiveRecord;

/**
 * Class JsonBehaviour
 *
 * @property \yii\db\ActiveRecord owner
 *
 * @package common\behaviors
 */
class JsonBehaviour extends Behavior {
    public $attributes = [];
    public $autoDetect = true;
    public $validators = [];
    public $defaultAttributeClass = DynamicModel::class;
    
    
    public function events() {
        return [
            ActiveRecord::EVENT_INIT => [$this, 'initialization'],
//            ActiveRecord::EVENT_AFTER_FIND      => null,
//            ActiveRecord::EVENT_BEFORE_INSERT   => null,
//            ActiveRecord::EVENT_BEFORE_UPDATE   => null,
//            ActiveRecord::EVENT_AFTER_INSERT    => null,
//            ActiveRecord::EVENT_AFTER_UPDATE    => null,
//            ActiveRecord::EVENT_BEFORE_VALIDATE => null,
//            ActiveRecord::EVENT_AFTER_VALIDATE  => null,
        ];
    }
    
    public function initialization() {
        if ($this->autoDetect) {
            $tableSchema = \Yii::$app->db->schema->getTableSchema($this->owner->tableName());
            $columns = $tableSchema->getColumnNames();
            foreach(array_keys($this->owner->attributes) as $attribute) {
                if (
                    in_array($attribute, $columns)
                    && !in_array($attribute, $this->attributes)
                    && $tableSchema->getColumn($attribute)->dbType === 'json'
                ) {
                    $this->attributes[] = $attribute;
//                    if (isset($this->_validators[$attribute]) && !isset($this->owner->validators)) {
//
//                    }
                }
            }
        }
    }
    
    public function serialize() {
    
    }
    
    public function unserialize() {
    
    }
    
    public function jsonBehaviorValidator($element) {
        xdebug_break();
    }
}