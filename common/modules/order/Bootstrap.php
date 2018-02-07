<?php
namespace common\modules\order;

use yii\base\BootstrapInterface;
use yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('order')) {
            $app->set('order', ['class' => 'common\modules\order\Order']);
        }

        if(empty($app->modules['gridview'])) {
            $app->setModule('gridview', [
                'class' => '\kartik\grid\Module',
            ]);
        }
        
        if (!isset($app->i18n->translations['order']) && !isset($app->i18n->translations['order*'])) {
            $app->i18n->translations['order'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/messages',
                'forceTranslation' => true
            ];
        }
        
        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        if (count($urlManagers)) {
            foreach ($urlManagers as $urlManager) {
                if (preg_match('#admin|backend#', $urlManager)) {
                    $rules = [
                        'order' => '/order/default/index',
//                        'cart/<_a:truncate|info>' => '/cart/default/<_a>',
//                        'cart/<_a:delete|create|update>' => '/cart/element/<_a>',
                    ];
                } else {
                    $rules = [];
                }
                /** @var \yii\web\UrlManager $urlManager */
                $urlManager = $app->get($urlManager);
                $urlManager->addRules($rules);
            }
        } else {
            $app->urlManager->addRules($rules);
        }
    }
}
