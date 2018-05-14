<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.01.2018
 * Time: 14:04
 */

if (isset($_GET['ggg'])) {
    phpinfo();
    exit;
}

if(!defined('APP_BASE_DIR')){
    throw new Exception('APP_BASE_DIR is not defined');
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

!defined('COMMON_DIR') and define('COMMON_DIR', dirname(__FILE__) . '/common');
!defined('CONFIG_CACHE') and define('CONFIG_CACHE', true);
define ('CONFIG_CACHE_FILE', __DIR__ . '/config.cache');
//$t = microtime(true);
/** @var \common\services\ConfigManager $configurator */
require_once(COMMON_DIR . '/services/ConfigManager.php');
/* TODO: Перенести кеширование в класс конфиг менеджера */
if (CONFIG_CACHE && file_exists(CONFIG_CACHE_FILE)) {
    $configurator = file_get_contents(CONFIG_CACHE_FILE);
    $configurator = unserialize($configurator);
} else {
    $configurator = new \common\services\ConfigManager(COMMON_DIR, dirname(__FILE__));
    $ser = serialize($configurator);
    file_put_contents(CONFIG_CACHE_FILE, $ser);
    unset($set);
}

$isTest = defined('YII_TEST') && YII_TEST;
$config = $configurator->getConfig(APP_BASE_DIR, $isTest);
//$t = microtime(true)-$t;
//echo $t;
//exit;

if (strpos(APP_BASE_DIR, 'console') !== false) {
    $class = \yii\console\Application::class;
} else {
    $class = \yii\web\Application::class;
}

return new $class($config);