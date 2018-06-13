<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 09.06.2018
 * Time: 12:36
 */

namespace common\modules\files;
use common\modules\baseModule\BaseModuleBootstrap;
use common\modules\files\components\FilesManager;
use common\modules\files\components\FileUrlRule;
use yii\base\Application;

class FilesBootstrap extends BaseModuleBootstrap {
    
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app) {
        if (!$app->hasModule(FilesModule::DEFAULT_ID)) {
            $app->setModule(FilesModule::DEFAULT_ID, ['class' => FilesModule::class]);
        }

        $module = $app->getModule(FilesModule::DEFAULT_ID);
        if (!$module->has(FilesModule::MANAGER_ID)) {
            $module->setComponents([
                FilesModule::MANAGER_ID => [
                    'class' => FilesManager::class
                ]
            ]);
        }
        
        $rules = [
            [
                'class' => FileUrlRule::class,
                'filesManagerModuleId' => FilesModule::DEFAULT_ID,
            ],
        ];

        self::addUrlRules(self::URL_RULES_TYPE_FRONTEND, $rules);
        self::addUrlRules(self::URL_RULES_TYPE_BACKEND, $rules);
    }
}