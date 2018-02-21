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
 */
class BaseActiveRecord extends ActiveRecord
{
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
        if (!isset($this->$name)) {
            $newName = $this->cc2lc($name);
            if (isset($this->$newName)) {
                $name = $newName;
            }
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
        if (!isset($this->$name)) {
            $newName = $this->cc2lc($name);
            if (isset($this->$newName)) {
                $name = $newName;
            }
        }
        return parent::__get($name);
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