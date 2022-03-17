<?php
define('APP_PATH', dirname(dirname(__DIR__)) . '/app/admin');
define('SITE_NAME', $_SERVER['SERVER_NAME']);
define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/vendor/autoload.php';

define('ENVIRON', 'develop');
//define('ENVIRON', 'product');

$app = new \Yaf\Application(ROOT_PATH . "/common/conf/" . ENVIRON . "/admin.ini");
$app->bootstrap()->run();