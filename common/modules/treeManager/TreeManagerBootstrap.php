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
            '/tree-manager/node/save' => '/tree-manager/node/save',
            '/tree-manager/node/manage' => '/tree-manager/node/manage',
            '/tree-manager/node/remove' => '/tree-manager/node/remove',
            '/tree-manager/node/move' => '/tree-manager/node/move',
        ];

        $manager->addRules($rules, true);
    }

}
