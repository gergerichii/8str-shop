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
    public static function startRegisterScript(View &$view, $pos = View::POS_END) {
        ob_start(function ($output) use ($view, $pos){
            $script = preg_replace("#^\s*<script\>(.+?)</script\>#is", '\1', $output);
            $view->registerJs($script, $pos);
        });
    }

    public static function endRegisterScript() {
        ob_end_clean();
    }
}