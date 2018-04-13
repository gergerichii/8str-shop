<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 09.04.2018
 * Time: 16:32
 */

namespace common\modules\counters;
use yii\base\Application;
use yii\base\BootstrapInterface;

class CountersBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     */
    public function bootstrap($app) {
        if(!$app->has('counters')) {
            $app->setModule('counters', ['class' => 'common\modules\counters\CountersModule']);
        }

        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        $feRules = [
        ];
        $beRules = [
            'counters' => '/counters/admin/default/index',
            'counters/<_a:\w+>' => '/counters/admin/default/<_a>',
            'counters/<_a:update|view>/<id:\d+>' => '/counters/admin/default/<_a>',
        ];
        if (count($urlManagers)) {
            foreach ($urlManagers as $urlManagerId) {
                /** @var \yii\web\UrlManager $urlManagerObj */
                $urlManagerObj = $app->get($urlManagerId);
                if (preg_match('#admin|backend#', $urlManagerId)) {
                    $urlManagerObj->addRules($beRules);
                } else {
                    $urlManagerObj->addRules($feRules);
                }
            }
        } else {
            $app->urlManager->addRules($feRules);
        }
    }
}