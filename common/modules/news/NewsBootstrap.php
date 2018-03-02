<?php

namespace common\modules\news;

use yii\base\BootstrapInterface;

/**
 * Class NewsBootstrap
 */
class NewsBootstrap implements BootstrapInterface
{

    /**
     * @inheritdoc
     * @param \yii\base\Application $app Application
     */
    public function bootstrap($app) {
        $manager = $app->getUrlManager();

        $rules = [
            '/news' => '/news/default/index',
        ];

        $manager->addRules($rules, true);
    }
}