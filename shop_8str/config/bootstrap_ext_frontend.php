<?php
/* Для изоляции переменных */
(function (){
    $themeRoot = dirname(__FILE__, 2);
    yii::setAlias('@themeRoot', $themeRoot);
    yii::setAlias('@themeViews', "$themeRoot/views");
    define('YII_THEME', basename($themeRoot));
})();
