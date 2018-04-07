<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

define('CONFIG_CACHE', true);
define('APP_BASE_DIR', realpath(__DIR__ . '/../'));
$app = require(__DIR__ . '/../../app.php');
$app->run();