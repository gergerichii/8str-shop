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
            
            $csrfName = \Yii::$app->request->csrfParam;
            $token = \Yii::$app->request->csrfToken;
            $script = "
                csrf_name = '{$csrfName}';
                csrf_value = '{$token}';
                
                $('input[name=' + csrf_name +']').val(csrf_value);
                $('meta[name=csrf-token]').attr('content', csrf_value)
            ";
            
            $app->view->on(View::EVENT_BEGIN_BODY, function () use ($script){
                if (!$this->notifyIsAdded) {
                    Yii2modAlert::widget();
                    $this->notifyIsAdded = true;
                    \Yii::$app->view->registerJs($script);
                }
            });
        }
    }
}