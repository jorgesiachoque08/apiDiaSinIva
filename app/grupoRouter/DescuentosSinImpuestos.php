<?php
// Validador Params
/* $AuthController */
$app->get(
  '/apiDiaSinIva/obtener_dsi',
  function() use ($AuthController,$DescuentosSinImpuestosController) {
    $user = $AuthController->verificarToken();
    if ($user) {
      return json_encode($DescuentosSinImpuestosController->obtener_dsi());
    }
  }
);

$app->get(
  '/apiDiaSinIva/listarProductosSinIva',
  function() use ($AuthController,$DescuentosSinImpuestosController) {
    $user = $AuthController->verificarToken();
    if ($user) {
      return json_encode($listarProductosSinIva->obtener_dsi());
    }
  }
);

$app->post(
  '/apiDiaSinIva/validarProducto/{cod_tercero}/{cod_mp}',
  function($cod_tercero,$cod_mp) use ($AuthController,$DescuentosSinImpuestosController,$request,$validador) {
    $user = $AuthController->verificarToken();
    if ($user) {
      if($request->getJsonRawBody()){
        $_POST = (array)$request->getJsonRawBody();
      }
      
      $validador->setRequeridos(["productos"]);
      $message = ["message" => [
          "productos" => "productos es requerida"
      ]];
      $validador->setMsjCamposRequerid($message);
      if($validador->Validando($_POST) === true){
        return json_encode($DescuentosSinImpuestosController->validarProducto($cod_tercero,$cod_mp,$_POST));
      }

    }
  }
);

$app->post(
  '/apiDiaSinIva/agregarCompraProductos/{cod_tercero}/{cod_mp}/{cod_factura}',
  function($cod_tercero,$cod_mp,$cod_factura) use ($AuthController,$DescuentosSinImpuestosController,$request,$validador) {
    $user = $AuthController->verificarToken();
    if ($user) {
      if($request->getJsonRawBody()){
        $_POST = (array)$request->getJsonRawBody();
      }
      
      $validador->setRequeridos(["compras"]);
      $message = ["message" => [
          "compras" => "compras es requerida"
      ]];
      $validador->setMsjCamposRequerid($message);
      if($validador->Validando($_POST) === true){
        return json_encode($DescuentosSinImpuestosController->agregarCompraProductos($cod_tercero,$cod_mp,$cod_factura,$_POST));
      }

    }
  }
);