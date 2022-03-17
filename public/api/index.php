<?php
date_default_timezone_set('Asia/Shanghai');

define('ENVIRON', 'develop');
//define('ENVIRON', 'product');
define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/vendor/autoload.php';

define('APP_PATH', ROOT_PATH . '/app/api');
define('SITE_NAME', $_SERVER['SERVER_NAME']);
$app = new \Yaf\Application(dirname(dirname(__DIR__)) . "/common/conf/" . ENVIRON . "/api.ini");
$app->bootstrap()->run();