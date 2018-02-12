<?php

namespace common\modules\treeManager;

/**
 * Tree manager bootstrap
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class TreeManagerBootstrap implements \yii\base\BootstrapInterface {

    /**
     * @inheritdoc
     * @param \yii\base\Application $app Application
     */
    public function bootstrap($app) {
        $manager = $app->getUrlManager();

        $rules = [
            '/treemanager/node/<_a:(save|manage|remove|move)>' => '/treemanager/node/<_a>',
        ];

        $manager->addRules($rules, true);
    }

}
