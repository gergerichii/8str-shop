<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 17.12.2017
 * Time: 21:57
 */

namespace common\config;

use common\widgets\Yii2modAlert;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\web\Application as WebApplication;
use yii\web\Controller;
use yii\web\Response;
use yii\web\View;

class setUp implements BootstrapInterface
{
    public $notifyIsAdded = false;
    
    public function bootstrap($app)
    {
        $container = \Yii::$container;
        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });
        
        if ($app instanceof WebApplication) {
            \Yii::$app->on(Controller::EVENT_AFTER_ACTION, function() {
                $request = \Yii::$app->getRequest();
                if (!($request->getIsAjax() || $request->getIsPatch() || preg_match('#login|logout|signup|/debug/|/captcha#', $request->getUrl()))) {
                    \Yii::$app->getUser()->setReturnUrl($request->getUrl());
                }
            });
            
            $app->view->on(View::EVENT_BEGIN_BODY, function (){
                if (!$this->notifyIsAdded) {
                    echo $this->getNotifyWidget();
                    $this->notifyIsAdded = true;
                }
            });
            $app->response->on(Response::EVENT_BEFORE_SEND, function ($event) use($app) {
                if (!$app->request->isAjax && !$this->notifyIsAdded) {
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