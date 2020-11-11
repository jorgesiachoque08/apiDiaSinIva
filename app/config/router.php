<?php

use Phalcon\Http\Request;

$request = new Request();

//Controladores
$AuthController = new AuthController();
$DescuentosSinImpuestosController = new DescuentosSinImpuestosController();


//Componentes
$validador = new Validador();


//Grupo de rutas
include APP_PATH.'/grupoRouter/DescuentosSinImpuestos.php';


$app->notFound(function () use ($app) {
    $response = new \Phalcon\Http\Response();
    $response->setContentType('application/json', 'utf-8');
    $response->setStatusCode(404);
    $response->send();
    return json_encode(["status"=>404,"mensaje"=>"Ruta no existe","data"=>false]);
});

$app->handle($_SERVER["REQUEST_URI"]);