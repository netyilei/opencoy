<?php
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');

define('ENVIRON', 'develop');
//define('ENVIRON', 'product');

define('ROOT_PATH', dirname(dirname(__DIR__)));
require_once ROOT_PATH . '/vendor/autoload.php';

define('APP_PATH', ROOT_PATH . '/app/console');
$app = new \Yaf\Application(dirname(dirname(__DIR__)) . "/common/conf/" . ENVIRON . "/console.ini");
$app->bootstrap();


$uri_r = explode('/', $argv[1]);
$moduleName = null;
if (count($uri_r) == 2) {
    list($controllerName, $actionName) = $uri_r;
} else if (count($uri_r) == 3) {
    list($moduleName, $controllerName, $actionName) = $uri_r;
} else {
    exit("Please enter the route to execute. Example: the php cli.php Index/Index!\n");
}

$params = [];
if (isset($argv[2]))
    parse_str($argv[2], $params);

unset($argv, $uri_r);
$request = new Yaf\Request\Simple('CLI', $moduleName, ucwords($controllerName), $actionName, $params);
\Yaf\Application::app()->getDispatcher()->dispatch($request);