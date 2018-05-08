<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17.04.2018
 * Time: 11:54
 */

namespace common\modules\baseModule;

use yii\base\BaseObject;
use yii\base\BootstrapInterface;

abstract class BaseModuleBootstrap extends BaseObject implements BootstrapInterface {
    
    const URL_RULES_TYPE_FRONTEND = 0;
    const URL_RULES_TYPE_BACKEND = 1;
    
    /** @var \yii\web\UrlManager[] */
    static protected $urlManagers = [];
    
    /**
     * Возвращает все Url менеджеры системы
     *
     * @return \yii\web\UrlManager[]
     * @throws \yii\base\InvalidConfigException
     */
    static function getUrlManagers(): array {
        if (empty(static::$urlManagers)) {
            $app = \Yii::$app;
            foreach (array_keys($app->components) as $componentName) {
                if (strPos($componentName, 'UrlManager') > 0) {
                    $key = (preg_match('#admin|backend#', $componentName)) ? self::URL_RULES_TYPE_BACKEND : self::URL_RULES_TYPE_FRONTEND;
                    $urlManagers[$key][] = $app->get($componentName);
                }
            }
        }
        
        return static::$urlManagers;
    }
    
    /**
     * Добавляет правила маршрутизации в систему в соответствующие по типу Url менеджеры
     *
     * @param int   $type [[self::URL_RULES_TYPE_FRONTEND]] or [[self::URL_RULES_TYPE_BACKEND]]
     * @param array $rules
     *
     * @throws \yii\base\InvalidConfigException
     */
    static function addUrlRules($type, $rules) {
        $urlManagers = self::getUrlManagers();
        if (isset($urlManagers[$type])) {
            /** @var \yii\web\UrlManager $urlManager */
            foreach($urlManagers[$type] as $urlManager) {
                $urlManager->addRules($rules);
            }
        } else {
            $currentIsBackend = preg_match('#^(?:admin|backend)#', \Yii::$app->request->hostName)
                || preg_match('#^/(?:admin|backend)#', \Yii::$app->request->hostName);
            
            if (
                ($type === self::URL_RULES_TYPE_BACKEND && $currentIsBackend)
                || ($type === self::URL_RULES_TYPE_FRONTEND && !$currentIsBackend)
            ) {
                \Yii::$app->urlManager->addRules($rules);
            }
        }
    }
}