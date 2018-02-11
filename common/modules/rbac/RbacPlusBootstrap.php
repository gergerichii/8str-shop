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
            '/rbac/rule' => '/rbac/rule/index',
            '/rbac/rule/view/<name:\w+>' => '/rbac/rule/view',
            '/rbac/rule/create' => '/rbac/rule/create',
            '/rbac/rule/update/<name:\w+>' => '/rbac/rule/update',
            '/rbac/rule/delete/<name:\w+>' => '/rbac/rule/delete',
            '/rbac/role' => '/rbac/role/index',
            '/rbac/role/view/<name:\w+>' => '/rbac/role/view',
            '/rbac/role/create' => '/rbac/role/create',
            '/rbac/role/update/<name:\w+>' => '/rbac/role/update',
            '/rbac/role/delete/<name:\w+>' => '/rbac/role/delete',
            '/rbac/permission' => '/rbac/permission/index',
            '/rbac/permission/view/<name:\w+>' => '/rbac/permission/view',
            '/rbac/permission/create' => '/rbac/permission/create',
            '/rbac/permission/update/<name:\w+>' => '/rbac/permission/update',
            '/rbac/permission/delete/<name:\w+>' => '/rbac/permission/delete',
            '/rbac/assignment' => '/rbac/assignment/index',
            '/rbac/assignment/assignment/<id:\d+>' => '/rbac/assignment/assignment',
        ];

        $manager->addRules($rules, true);
    }

}
