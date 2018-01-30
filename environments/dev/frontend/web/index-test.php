<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

define('CONFIG_CACHE', true);
define('APP_BASE_DIR', realpath(__DIR__ . '/../'));
define('YII_TEST', realpath(__DIR__ . '/../'));
$app = require(__DIR__ . '/../../app.php');
$app->run();
