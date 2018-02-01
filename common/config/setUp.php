<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 17.12.2017
 * Time: 21:57
 */

namespace common\config;

use common\modules\catalog\Module;
use yii\base\BootstrapInterface;
use Yii;
use yii\mail\MailerInterface;

class setUp implements BootstrapInterface {
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

//        $container->setSingleton(CatalogService::className(), [], [
//            'id' => 'catalogService',
//        ]);
    }
}