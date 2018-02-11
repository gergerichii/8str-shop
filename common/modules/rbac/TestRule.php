<?php

namespace common\modules\rbac;

/**
 * Rule for test
 *
 * @author Andriy Ivanchenko <ivanchenko.andriy@gmail.com>
 */
class TestRule extends \yii\rbac\Rule {

    /**
     * Name
     *
     * @var string
     */
    public $name = 'testrule';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params): bool {
        return false;
    }

}
