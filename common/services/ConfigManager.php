<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23.01.2018
 * Time: 13:17
 */

namespace common\services;

use \Yii;
use yii\helpers\ArrayHelper;

require dirname(__FILE__) . '/../helpers/StringsHelper.php';

/**
 * Менеджер конфигов. Перед загнузкой
 *
 * Class ConfigManager
 * @package common\services
 */
class ConfigManager {
    
    protected const SKIP_DIRS = ['.', '..', '.ide', '.git', '.vagrant', 'environments'];
    protected const APP_STRUCTURE = [
        'appName' => '',
        'bootstrap' => [],
        'config' => [],
        'test_config' => [],
        'sources' => [
            'main' => [
                'info' => [],
                'source' => [],
            ],
            'test' => [
                'info' => [],
                'source' => [],
            ],
            'params' => [
                'info' => [],
                'source' => [],
            ],
            'bootstrap' => [
                'info' => [],
                'source' => [],
            ],
            'local' => [
                'main' => [
                    'info' => [],
                    'source' => [],
                ],
                'test' => [
                    'info' => [],
                    'source' => [],
                ],
                'params' => [
                    'info' => [],
                    'source' => [],
                ],
                'bootstrap' => [
                    'info' => [],
                    'source' => [],
                ],
            ],
        ]
    ];
    protected const APP_SOURCE_FILES = [
        'main' => ['source' => 'sources.main.source', 'info' => 'sources.main.info'],
        'test' => ['source' => 'sources.test.source', 'info' => 'sources.test.info'],
        'params' => ['source' => 'sources.params.source', 'info' => 'sources.params.info'],
        'bootstrap' => ['source' => 'sources.bootstrap.source', 'info' => 'sources.bootstrap.info'],
        'local/main' =>['source' => 'sources.local.main.source', 'info' => 'sources.local.main.info'],
        'local/test' => ['source' => 'sources.local.test.source', 'info' => 'sources.local.test.info'],
        'local/params' => ['source' => 'sources.local.params.source', 'info' => 'sources.local.params.info'],
        'local/bootstrap' => ['source' => 'sources.local.bootstrap.source', 'info' => 'sources.local.bootstrap.info'],
    ];
    
    /** @var string */
    protected $_rootDir;
    /** @var string */
    protected $_commonDir;
    /** @var array */
    protected $_apps = [];
    /** @var array */
    protected $_confInfos = [];
    /** @var array */
    protected $_notSatedFiles = [];
    
    /**
     * Configurator constructor.
     *
     * Тут собираются все конфиги для всех приложений для дальнейшего кеширования через сериализацию всего объекта
     *
     * @param string $commonDir путь до общей для всех приложений папки
     * @param string $rootDirPath Корень всех приложений
     *
     * @throws \Exception
     */
    public function __construct(string $commonDir, string $rootDirPath){
        $commonDir = preg_replace('#[/\\\]$#', '', $commonDir);
        $this->_rootDir = $rootDirPath;
        $this->_commonDir = $commonDir;
        $iRootDir = opendir($rootDirPath);
        
        /* Предподготавливаем структуры для общих конфигов */
        $this->_apps['common'] = self::APP_STRUCTURE;
        $this->_apps['common']['appName'] = 'common';
        $this->_apps['common_web'] = self::APP_STRUCTURE;
        $this->_apps['common_web']['appName'] = 'common_web';
        $this->_apps['common_console'] = self::APP_STRUCTURE;
        $this->_apps['common_console']['appName'] = 'common_console';

        $apps = [];
        /* Обходим все каталоги где есть конфиги - это наши приложения */
        while($appDirName = readdir($iRootDir)) {
            $appPath = "{$rootDirPath}/{$appDirName}";
            /* Задаем путь к папке конфигов для приложения */
            $appConfPath = "$appPath/config";
            /* Если нет папки конфигов, значит это вообще не приложение =) */
            if (
                in_array($appDirName, self::SKIP_DIRS) || !is_dir($appPath)
                || !file_exists($appConfPath)
            ) continue;
            $apps[] = ['appConfPath' => $appConfPath, 'appDirName' => $appDirName];
            yii::setAlias('@' . $appDirName, $appPath);
        }
        
        foreach ($apps as $appParams) {
            /* Начинаем перебирать все конфиги приложения и собирем их в единый массив */
            call_user_func_array([$this, 'aggregateConfigs'], $appParams);
        }
        
        /* Вычисляем все конфиги подцепляя все зависимости */
        $this->calculateConfigs();
        /* Создаем необходимые алиасы для общей папки и всех приложений (для автолоада) */
        unset($this->_confInfos);
    }
    
    /**
     * Собирает все конфиги приложения в единый массив $this->_apps[$appName]
     * со структурой указанной в массиве self::APP_STRUCTURE
     *
     * @param string $confDirPath
     * @param string $appName
     *
     * @throws \Exception
     */
    protected function aggregateConfigs(string $confDirPath, string $appName) {
        /* Для каждого приложения начинаем собирать инфу */
        if (!isset($this->_apps[$appName]))
            $this->_apps[$appName] = self::APP_STRUCTURE;
            $this->_apps[$appName]['appName'] = $appName;
        if (!file_exists($confDirPath) && !is_dir($confDirPath)) {
            throw new \Exception("Папка $confDirPath не существует");
        }

        $iConfDir = opendir($confDirPath);
        /* Читаем все файлы в каталоге */
        while ($confFileName = readdir($iConfDir)) {
            $confFilePath = "{$confDirPath}/{$confFileName}";
            
            /* Нас интересуют только файлы или папка local */
            if (is_dir($confFilePath)){
                if ($confFileName !== 'local') continue;
                $this->aggregateConfigs($confFilePath, $appName);
                continue;
            }
            
            /*Нас интересуют олько файлы конфигов (main, test, params и bootstrap)*/
            if (!$confInfo = $this->getConfInfo($confFilePath)) continue;
            
            /* Сразу сохраняем путь к файлу для бутстрапов, для дальнейшего их вызова */
            if ($confInfo['confType'] === 'bootstrap') {
                $value = [$confFilePath];
            }
            /* И содержимое файлов конфигов */
            else {
                /** @noinspection PhpIncludeInspection */
                $value = require $confFilePath;
                if (!is_array($value)) continue;
            }
            /* Сохраняем все в служебной информации */
            $sourceFile =  self::APP_SOURCE_FILES[$confInfo['confName']];
            ArrayHelper::setValue($this->_apps[$appName], $sourceFile['source'], $value);
            ArrayHelper::setValue($this->_apps[$appName], $sourceFile['info'], $confInfo);
        }

        /* Если это оснавная для всех приложений папка, то проверяем в ней наличие общих
            конфигов для web и console приложений */
        if ($appName === 'common') {
            if (file_exists($confDirPath . '/web')) {
                $this->aggregateConfigs($confDirPath . '/web', 'common_web');
            }
            if (file_exists($confDirPath . '/console')) {
                $this->aggregateConfigs($confDirPath . '/console', 'common_console');
            }
        }
    }
    
    /**
     * Выполняет вычисление значений конечных конфигов для приложений с учетом зависимостей и общих конфигов
     * Должен вызываться ТОЛЬКО после $this->aggregateConfigs()
     */
    protected function calculateConfigs() {
        /* Переменные для укорочения кода и улучшения читабельности */
        $getValue = 'yii\helpers\ArrayHelper::getValue';
        $setValue = 'yii\helpers\ArrayHelper::setValue';
        $merge = 'yii\helpers\ArrayHelper::merge';
        $ASF = self::APP_SOURCE_FILES;
        $apps = &$this->_apps;

        $appNames = array_keys($apps);

        /* Перебираем все приложения */
        while ($appName = array_shift($appNames)) {
            $app = &$apps[$appName];
            $dependent = false;
            /* Проверка на зависимости и их сборка, если зависимости удовлетворены */
            foreach($ASF as $sourceFile) {
                $tmpInfo = $getValue($app, $sourceFile['info']);
                $dependent = isset($tmpInfo['extApp']) ? $tmpInfo['extApp'] : false;
                if ($dependent) {
                    /* Если зависимое приложение еще не собрано, то пока отложим сборку этого приложения */
                    if (in_array($dependent, $appNames)) break;
                    $dependent = false;
                    $extValue = $getValue($apps[$tmpInfo['extApp']], $sourceFile['source']);
                    $newValue = $merge(
                        $extValue,
                        $getValue($app, $sourceFile['source'])
                    );
                    $setValue($app, $sourceFile['source'], $newValue);
                }
            }
            /* Если зависимое приложение еще не собрано, то текущее приложение перенесем в конец очереди обработки */
            if ($dependent) {
                array_push($appNames, $appName);
                continue;
            }
            
            /*собираем основные конфиги с учетом локальных конфигов*/
            $setValue(
                $app, $ASF['main']['source'],
                $merge(
                    $getValue($app, $ASF['main']['source']),
                    $getValue($app, $ASF['local/main']['source'])
                )
            );
            $setValue(
                $app, $ASF['params']['source'],
                $config['params'] = $merge(
                    $getValue($app, $ASF['params']['source']),
                    $getValue($app, $ASF['local/params']['source'])
                )
            );
            /* Цепляем к основному конфигу параметры */
            $setValue(
                $app,
                "{$ASF['main']['source']}.params",
                $merge(
                    $getValue($app, "{$ASF['main']['source']}.params", []),
                    $getValue($app, $ASF['params']['source'])
                )
            );
            $setValue(
                $app, $ASF['test']['source'],
                $merge(
                    $getValue($app, $ASF['main']['source']),
                    $getValue($app, $ASF['test']['source']),
                    $getValue($app, $ASF['local/test']['source'])
                )
            );
            $setValue(
                $app, $ASF['bootstrap']['source'],
                $merge(
                    $getValue($app, $ASF['bootstrap']['source']),
                    $getValue($app, $ASF['local/bootstrap']['source'])
                )
            );


            /* Собираем глобальные зависимости от общеих конфигов */
            if ($app['appName'] !== 'common') {
                if (in_array($app['appName'], ['common_web', 'common_console'])) {
                    $extApp = 'common';
                } elseif (strpos($app['appName'], 'console') !== false) {
                    $extApp = 'common_console';
                } else {
                    $extApp = 'common_web';

                    /* При необходимости генерим ID приложения */
                    if (!$getValue($app, "{$ASF['main']['source']}.id", false)) {
                        $setValue($app, "{$ASF['main']['source']}.id", "app_{$app['appName']}");
                    }
                }

                $tmpInfo = $getValue($app, $ASF['main']['info']);
                if (empty($tmpInfo['extApp'])) {
                    $extConfig = $apps[$extApp]['config'];
                    $setValue(
                        $app,
                        $ASF['main']['source'],
                        $merge($extConfig, $getValue($app, $ASF['main']['source']))
                    );
                }

                $tmpInfo = $getValue($app, $ASF['test']['info']);
                if (empty($tmpInfo['extApp'])) {
                    $extTestConfig = $apps[$extApp]['test_config'];
                    $setValue(
                        $app,
                        $ASF['test']['source'],
                        $merge($extTestConfig, $getValue($app, $ASF['test']['source']))
                    );
                }

                $tmpInfo = $getValue($app, $ASF['bootstrap']['info']);
                if (empty($tmpInfo['extApp'])) {
                    $extBootstrap = $apps[$extApp]['bootstrap'];
                    $setValue(
                        $app,
                        $ASF['bootstrap']['source'],
                        $merge($extBootstrap, $getValue($app, $ASF['bootstrap']['source']))
                    );
                }
            }

            /* Помещяем собранные конфиги на их места */
            $app['config'] = $getValue($app, $ASF['main']['source']);
            $app['test_config'] = $getValue($app, $ASF['test']['source']);
            $app['bootstrap'] = $getValue($app, $ASF['bootstrap']['source']);
        }
        
        /* Теперь нам не нужны сурсы, Освободим память */
        foreach ($apps as &$app) {
            unset($app['sources']);
        }
    }
    
    /**
     * @param string $confFilePath
     *
     * @param string $appName
     *
     * @return array|bool|mixed
     */
    protected function getConfInfo(string $confFilePath, string $appName = '') {
        if (isset($this->_confInfos[$confFilePath])) return $this->_confInfos[$confFilePath];
        
        $confFileInfo = pathinfo($confFilePath);
        if ($confFileInfo['extension'] !== 'php') return false;
        
        $pregTypesPattern = implode('|', array_keys(self::APP_SOURCE_FILES));
        $pregTypesPattern = preg_replace('#\|\w+/[^\|]+#', '', $pregTypesPattern);
        preg_match(
            "#^(?P<confType>{$pregTypesPattern})(?:_ext_(?P<extApp>\w+?))?$#",
            $confFileInfo['filename'], $confInfo
        );
        
        if (!isset($confInfo['confType'])) return false;
        
        $ret = $confFileInfo;
        foreach ($confInfo as $key => $value) {
            if (!is_int($key)) $ret[$key] = $value;
        }

        if (empty($appName)) {
            $appName = preg_replace('#^.*?/(\w+)/config.*$#', '\1', $ret['dirname']);
        }
        
        $ret['nesting'] = preg_replace('#(.+?config(?:/web|/console)?)(?:/(.*))?$#', '\2', $ret['dirname']);
        $ret['confName'] = (!empty($ret['nesting']) ? $ret['nesting'] . '/' : '') . $ret['confType'];
        
        $ret['appName'] = $appName;
        $ret['fullPath'] = $confFilePath;
        
        $this->_confInfos[$confFilePath] = $ret;
        return $ret;
    }
    
    /**
     *
     */
    public function __wakeup(){
        Yii::setAlias('@common', $this->_commonDir);
        /* Перебераем все конфиги */
        foreach (array_keys($this->_apps) as $appName) {
            /* Создаем алиасы для каждого приложения */
            if (strpos($appName,  'common') === false)
                Yii::setAlias('@' . $appName, $this->_rootDir . '/' . $appName);
        }
    }

    /**
     * @param string $appPath
     *
     * @param bool $isTest
     * @return bool
     */
    public function getConfig(string $appPath, $isTest = false) {
        $configName = ($isTest) ? 'test_config' : 'config';
        /* Делаем подмену urlManager`ов для того чтобы из любого приложения можно было генерить ссылки
         в другие приложения */
        $urlManagers = [];
        foreach ($this->_apps as &$app) {
            if ( strpos($app['appName'], 'common') !== false ) continue;

            if (empty($app[$configName]['components']['urlManager']))
                $urlManager = [];
            else {
                $urlManager = $app[$configName]['components']['urlManager'];
            }
            if (!isset($urlManager['class'])) {
                $urlManager['class'] = 'yii\web\UrlManager';
            }

            $newId = $app['appName'] . 'UrlManager';
            $app[$configName]['components'][$newId] = $urlManager;
            $urlManagers[$newId] = $urlManager;
            $app[$configName]['components']['urlManager'] = (function ($appName){
                return function () use ($appName) {
                    return Yii::$app->get("{$appName}UrlManager");
                };
            })($app['appName']);
        }

        $appPath = preg_replace('#[/\\\]$#', '', $appPath);
        $appName = basename($appPath);
        $ret = isset($this->_apps[$appName][$configName]) ? $this->_apps[$appName][$configName] : false;
        if ($ret) {
            $ret['components'] = ArrayHelper::merge($ret['components'], $urlManagers);
            if (isset($this->_apps[$appName]['bootstrap'])) {
                foreach ((array) $this->_apps[$appName]['bootstrap'] as $bootstrapFile)
                    /** @noinspection PhpIncludeInspection */
                    require_once $bootstrapFile;
            }
        }
        return $ret;
    }
}
