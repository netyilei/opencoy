<?php
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app/admin');
define('SITE_NAME', $_SERVER['SERVER_NAME']);
require_once ROOT_PATH . '/vendor/autoload.php';

define('ENVIRON', 'develop');
//define('ENVIRON', 'product');

$app = new \Yaf\Application(ROOT_PATH . "/common/conf/" . ENVIRON . "/admin.ini");
$app->bootstrap()->run();