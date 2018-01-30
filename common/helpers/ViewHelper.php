<?php
/**
 * Created by PhpStorm.
 * User: George
 * Date: 13.01.2018
 * Time: 16:54
 */

namespace common\helpers;


use yii\web\View;

class ViewHelper {
    public static function startRegisterScript(View &$view) {
        ob_start(function ($output) use ($view){
            $script = preg_replace("#^\s*<script\>(.+?)</script\>#is", '\1', $output);
            $view->registerJs($script);
        });
    }

    public static function endRegisterScript() {
        ob_end_clean();
    }
}