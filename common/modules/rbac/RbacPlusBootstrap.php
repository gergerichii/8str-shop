<?php

namespace common\modules\rbac;

use yii\base\BootstrapInterface;

/**
 * Hook with application bootstrap stage
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class RbacPlusBootstrap implements BootstrapInterface {

    /**
     * @inheritdoc
     * @param \yii\base\Application $app Application
     */
    public function bootstrap($app) {
        $manager = $app->getUrlManager();

        $rules = [
            '/rbac/assignment/assignment/<id:\d+>' => '/rbac/assignment/assignment',
            '/rbac/<_c:(rule|permission|role|assignment)>' => '/rbac/<_c>/index',
            '/rbac/<_c:(rule|permission|role)/create>' => '/rbac/<_c>/create',
            '/rbac/<_c:(rule|permission|role)>/<_a:(view|update|delete)>/<name:\w+>' => '/rbac/<_c>/<_a>',
        ];

        $manager->addRules($rules, true);
    }

}
