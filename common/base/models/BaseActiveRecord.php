<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 21.02.2018
 * Time: 12:03
 */

namespace common\base\models;
use yii\db\ActiveRecord;

/**
 * Class BaseActiveRecord
 *
 * Позволяет обращаться к аттрибутам вида lower_case_style через псевдонимы вида camelCaseStyle
 * Так-же, если в правилах указаны аттрибуты lower_case_style, то в эти правила будут так-же применимы и к их
 * псевдонимам и псевдонимы будут в массиве safeAttributes
 *
 * @package common\base\models
 * @property int $id [int(11)]
 */
class BaseActiveRecord extends ActiveRecord
{
    private $_loadDefaults = false;
    
    /**
     * BaseActiveRecord constructor.
     *
     * @param array|boolean $config если $config == false то значения по умолчанию не будут заполняться автоматически
     */
    public function __construct($config = []) {
        if ((!is_bool($config) && is_array($config) && !isset($config['loadDefaults'])) || (is_bool($config) && $config !== false)) {
            $this->_loadDefaults = true;
        }
        if (is_array($config)) {
            unset($config['loadDefaults']);
        } elseif (is_bool($config)) {
            $config = [];
        }
        parent::__construct($config);
    }
    
    /**
     *
     */
    public function init() {
        parent::init();
        if ($this->_loadDefaults) {
            $this->loadDefaultValues();
        }
    }
    
    /**
     * @return array
     */
    protected function jsonAttributes() {
        return [];
    }
 
    /**
     * Переводит значение имени атрибута из camelCaseStyle в lower_case_style
     *
     * @param $name
     *
     * @return null|string|string[]
     */
    protected function cc2lc($name){
        return preg_replace_callback('#([a-z])([A-Z])#', function ($matches){
            return $matches[1] . '_' . strtolower($matches[2]);
        }, $name);
    }
    
    /**
     * Добавлена возможность обращаться к полям через camelCaseStyle
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value) {
        if (!$this->hasAttribute($name)) {
            $newName = $this->cc2lc($name);
            if ($this->hasAttribute($newName)) {
                $name = $newName;
            }
        }
        
        if (in_array($name, $this->jsonAttributes())) {
            $options = JSON_FORCE_OBJECT && JSON_NUMERIC_CHECK
                && JSON_PARTIAL_OUTPUT_ON_ERROR && JSON_PRESERVE_ZERO_FRACTION;
            $value = json_encode($value, $options);
        }
        
        parent::__set($name, $value);
    }
    
    /**
     * Добавлена возможность обращаться к полям через camelCaseStyle
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name) {
        if (!$this->hasAttribute($name)) {
            $newName = $this->cc2lc($name);
            if ($this->hasAttribute($newName)) {
                $name = $newName;
            }
        }
        
        $value =  parent::__get($name);

        if (in_array($name, $this->jsonAttributes())) {
            $value = json_decode($value, true);
        }
        
        return $value;
    }
    
    /**
     * Returns the attribute names that are safe to be massively assigned in the current scenario.
     * Так-же добавлены алиасы в стиле camelCase на атрибуты в стиле name_attribute
     *
     * @return string[] safe attribute names
     */
    public function safeAttributes()
    {
        $scenario = $this->getScenario();
        $scenarios = $this->scenarios();
        if (!isset($scenarios[$scenario])) {
            return [];
        }
        $attributes = [];
        foreach ($scenarios[$scenario] as $attribute) {
            if ($attribute[0] !== '!' && !in_array('!' . $attribute, $scenarios[$scenario])) {
                $attributes[] = $attribute;
                $ucAttribute = preg_replace_callback('#_([a-z])#', function ($matches){
                    return strtoupper($matches[1]);
                }, $attribute);
                if ($ucAttribute !== $attribute) {
                    $attributes[] = $ucAttribute;
                }
            }
        }

        return $attributes;
    }
}