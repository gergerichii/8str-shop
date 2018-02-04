<?php
namespace common\modules\cart;

use yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public function init()
    {
        parent::init();
    }
    
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        yii::$container->set('common\modules\cart\interfaces\Cart', 'common\modules\cart\models\Cart');
        yii::$container->set('common\modules\cart\interfaces\Element', 'common\modules\cart\models\CartElement');
        yii::$container->set('cartElement', 'common\modules\cart\models\CartElement');

        if (!isset($app->i18n->translations['cart']) && !isset($app->i18n->translations['cart*'])) {
            $app->i18n->translations['cart'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__.'/messages',
                'forceTranslation' => true
            ];
        }

        if (!$app->get('cartService', false)) {
            $app->setComponents([
                'cartService' => [
                    'class' => CartService::className(),
                ],
            ]);
        }

        $urlManagers = [];
        foreach (array_keys($app->components) as $componentName) {
            if (strPos($componentName, 'UrlManager') > 0)
                $urlManagers[] = $componentName;
        }

        $rules = [
            'cart' => '/cart/default/index',
            'cart/<_a:truncate|info>' => '/cart/default/<_a>',
            'cart/<_a:delete|create|update>' => '/cart/element/<_a>',
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
