<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 17.12.2017
 * Time: 21:57
 */

namespace common\config;

use common\modules\catalog\Module;
use common\widgets\Yii2modAlert;
use yii\base\BootstrapInterface;
use Yii;
use yii\mail\MailerInterface;
use yii\web\Response;
use yii\web\View;
use yii2mod\notify\BootstrapNotify;

class setUp implements BootstrapInterface
{
    public $notifyIsAdded = false;
    
    public function bootstrap($app)
    {
        $container = Yii::$container;
        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });
        
        if ($app instanceof yii\web\Application) {
            $app->view->on(View::EVENT_BEGIN_BODY, function (){
                if (!$this->notifyIsAdded) {
                    echo $this->getNotifyWidget();
                    $this->notifyIsAdded = true;
                }
            });
            $app->response->on(Response::EVENT_BEFORE_SEND, function ($event) use($app) {
                if (!$app->request->isAjax) {
                    $app->response->content = $this->getNotifyWidget() . $app->response->content;
                }
            });
        }
    }
    
    /**
     * @return string
     * @throws \Exception
     */
    public function getNotifyWidget() {
        return Yii2modAlert::widget();
    }
}