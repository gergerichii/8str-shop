<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24.05.2018
 * Time: 14:14
 */

namespace common\cacheDependencies;
use yii\base\Exception;

class DependencyFactory {
    private static $_dependencies = [];
    
    /**
     * @param $depName
     *
     * @return \yii\caching\Dependency
     * @throws \yii\base\Exception
     */
    public static function getDependency($depName) {
        $className = ucfirst(mb_strtolower($depName)) . 'Dependency';
        $className = __NAMESPACE__ . '\\' .$className;
        if (class_exists($className)) {
            if (!isset(self::$_dependencies[$depName])) {
                self::$_dependencies[$depName] = new $className;
            }
            $ret = self::$_dependencies[$depName];
        } else {
            throw new Exception('Нет зависимости с именем ' . $depName);
        }
        
        return $ret;
    }
}