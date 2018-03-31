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

        $feRules = [
            'order' => '/order/default/index',
        ];
        $beRules = [
            'order' => '/order/default/index',
            'order/<_a:[\w]+>' => '/order/default/<_a>',
            'order/<_c:[\w]+>/<_a:[\w]+>' => '/order/<_c>/<_a>',
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
