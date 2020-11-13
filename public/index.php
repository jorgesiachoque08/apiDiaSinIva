<?php

use Phalcon\Mvc\Micro;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
require __DIR__ . '/../vendor/autoload.php';
include APP_PATH .'/config/services.php';
$config = $di->getConfig();
include APP_PATH .'/config/loader.php';
$app = new Micro($di);
/* if ($app->request->getHeader('ORIGIN')) {
    $origin = $app->request->getHeader('ORIGIN');
} else {
    $origin = '*';
}

$app->response->setHeader('Access-Control-Allow-Origin',$origin
    )->setHeader(
        'Access-Control-Allow-Methods',
        'GET,PUT,POST,DELETE,OPTIONS'
    )->setHeader(
        'Access-Control-Allow-Headers',
        'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization'
    )->setHeader(
        'Access-Control-Allow-Credentials', 
        'true'
    ); */

/* $app->before(
    function () use ($app) {
    
        $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';
        $app->response->setHeader("Access-Control-Allow-Origin", $origin)
            ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
            ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
            ->setHeader("Access-Control-Allow-Credentials", true);
    
    
        return true;
    }); */
    $app->options('/{catch:(.*)}', function() use ($app) { $app->response->setStatusCode(200, "OK")->send(); });
include APP_PATH .'/config/router.php';
