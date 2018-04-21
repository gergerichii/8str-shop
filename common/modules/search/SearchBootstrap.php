<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17.04.2018
 * Time: 11:47
 */

namespace common\modules\search;

use common\modules\baseModule\BaseModuleBootstrap;
use yii\base\Application;

/**
 * Class SearchBootstrap
 *
 * @package common\modules\search
 */
class SearchBootstrap extends BaseModuleBootstrap {
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app) {
        if(!$app->has('searchEngine')) {
            $app->set('searchEngine', ['class' => 'common\modules\order\Order']);
        }

        $feRules = [
            'search' => '/search/default/index',
            'search/<_a:\w+>' => '/search/default/<_a>',
        ];
        $beRules = [
            'search' => '/search/default/index',
            'search/setup' => '/search/admin/default/index',
        ];
        
        self::addUrlRules(self::URL_RULES_TYPE_FRONTEND, $feRules);
        self::addUrlRules(self::URL_RULES_TYPE_BACKEND, $beRules);
    }
}