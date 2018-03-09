<?php

namespace common\modules\catalog;

use yii\base\BootstrapInterface;
use yii\web\Application;

/**
 * Class bootstrap catalog admin
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class CatalogAdminBootstrap implements BootstrapInterface
{

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        /** @var Application $app */
        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        $rules = [
            '/catalog' => '/catalog/default/index',
            '/catalog/<_a:(rubrics|create|delete-image|upload-image)>' => '/catalog/default/<_a>',
            '/catalog/<_a:(view|delete|update)>/<id:\d+>' => '/catalog/default/<_a>',
        ];

        if (count($urlManagers)) {
            foreach ($urlManagers as $urlManager) {
                /** @var \yii\web\UrlManager $urlManager */
                $urlManager = $app->get($urlManager);
                $urlManager->addRules($rules);
            }
        } else {
            $app->urlManager->addRules($rules);
        }
    }

}
