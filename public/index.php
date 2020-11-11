<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, UPDATE, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Authorization, Content-Type, Accept, Origin, User-Agent, Cache-Control, X-Requested-With, Access-Control-Allow-Origin");

use Phalcon\Mvc\Micro;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require __DIR__ . '/../vendor/autoload.php';
include APP_PATH .'/config/services.php';
$config = $di->getConfig();
include APP_PATH .'/config/loader.php';
$app = new Micro($di);
include APP_PATH .'/config/router.php';
