<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 31.05.2018
 * Time: 18:46
 */

namespace common\modules\files;

use common\modules\baseModule\BaseModuleBootstrap;
use common\modules\files\components\FileUrlRule;
use yii\base\Application;
use yii\base\BootstrapInterface;

class FilesBootstrap extends BaseModuleBootstrap implements BootstrapInterface {
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @throws \yii\base\InvalidConfigException
     */
    public
    function bootstrap(
        $app
    ) {
        $urlManagers = [];
        foreach(array_keys($app->components) as $componentName) {
            if(strPos($componentName, 'UrlManager') > 0) {
                $urlManagers[] = $componentName;
            }
        }
        $rules = [
            [
                'class' => FileUrlRule::class,
                'filesManagerModuleId' => $this->id,
            ],
        ];
        if(count($urlManagers)) {
            foreach($urlManagers as $urlManager) {
                /** @var \yii\web\UrlManager $urlManager */
                $urlManager = $app->get($urlManager);
                $urlManager->addRules($rules);
            }
        } else {
            $app->urlManager->addRules($rules);
        }
    }
}