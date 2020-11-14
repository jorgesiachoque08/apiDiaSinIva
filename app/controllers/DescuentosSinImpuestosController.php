<?php

use Phalcon\Mvc\Controller;
use App\Models\Categorias;
class DescuentosSinImpuestosController extends Controller {

    public function obtener_dsi()
    {
        try{
            $sql = "SELECT 
            dsi.cod
            FROM descuentos_sin_impuestos dsi
            where current_timestamp() between CONCAT(dsi.fecha_inicial, ' ', dsi.hora_inicial) and CONCAT(dsi.fecha_final, ' ', dsi.hora_final)";
            $data = $this->db->fetchAll($sql);

            if(count($data) > 0){
                $codigo = 200;
                $mensaje = "Ok";
                $retorno = $data[0]["cod"];
            }else{
                $codigo = 404;
                $mensaje = "No se encontraron resultados";
                $retorno = null;

            }
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" =>$mensaje,
                "data" =>$retorno
            ));
        } catch (Exception $ex) {
            $codigo = 500;
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" => 'Error en el servidor',
                "data"=>false
            ));
        }
        $this->response->setStatusCode($codigo);
        $this->response->send();
    }

    public function validarProducto($cod_tercero,$cod_mp,$params)
    {
        try{
            $productos = json_decode($params["productos"]);
            if(is_array($productos)){
                if(count($productos) > 0){
                    $sql = "SELECT 
                        dsi.*
                        FROM descuentos_sin_impuestos dsi
                        where current_timestamp() between CONCAT(dsi.fecha_inicial, ' ', dsi.hora_inicial) and CONCAT(dsi.fecha_final, ' ', dsi.hora_final)";
                    $dsi = $this->db->fetchAll($sql);

                    if(count($dsi) > 0){
                        $productoValidador = [];
                        $ctrl = true;
                        $sql = "select 
                                    p.cod,
                                    i.valor as valor_impuesto
                                from
                                    descuentos_sin_impuestos dsi
                                    inner join limites_param_fisc lpf on lpf.cod=dsi.cod_lim_pf
                                    inner join detalles_lim_param_fisc dlpf on dlpf.cod_lim_pf =lpf.cod
                                    inner join valores_parametros_fiscales vpf on vpf.cod_parametro_fiscal=lpf.cod
                                    inner join descuentos_sin_impuesto_mp dsimp on dsimp.cod_descuento_sin_impuesto=dsi.cod
                                    inner join productos p on p.cod_categoria=dlpf.cod_categoria
                                    inner join impuestos i on p.cod_impuesto = i.cod
                                    inner join medios_pagos mp on dsimp.cod_medio_pago=mp.cod
                                where
                                    mp.object_id='".$cod_mp."'
                                    and dsi.cod = ".$dsi[0]["cod"]."
                                    and p.cod in ";
                        $sqlCodProductos = "(";
                        foreach ($productos as $value) {
                            if(isset($value->cod_producto)){
                                
                                if (!isset($productoValidador[$value->cod_producto])) {
                                    $productoValidador[$value->cod_producto] = $value;
                                    $sqlCodProductos .= $value->cod_producto.",";
                                }
                            }else{
                                $ctrl = false;
                                break;
                            }

                        }

                        if($ctrl){
                            $sqlCodProductos = substr($sqlCodProductos,0,-1);
                            $sql = $sql.$sqlCodProductos.")";
                            $productosSinImp = $this->db->fetchAll($sql);
                            if(count($productosSinImp) > 0){

                                $sqlCodProductos = "";
                                $sql = "SELECT 
                                    tdsi.object_id_tercero,
                                    tdsi.cod_descuento_sin_impuesto,
                                    tdsi.cod_producto,
                                    sum(tdsi.cantidad) as cantidad
                                FROM terceros_descuentos_sin_impuestos as tdsi
                                where tdsi.object_id_tercero = '".$cod_tercero."' and tdsi.cod_producto in (";
                                foreach ($productosSinImp as $value) {
                                    $sqlCodProductos .= $value["cod"].",";
                                }
                                $sqlCodProductos = substr($sqlCodProductos,0,-1);
                                $sql = $sql.$sqlCodProductos;
                                $sql.= ") and tdsi.cod_descuento_sin_impuesto = ".$dsi[0]["cod"]."
                                GROUP BY tdsi.object_id_tercero,tdsi.cod_descuento_sin_impuesto,tdsi.cod_producto";
                                $data = $this->db->fetchAll($sql);
                                $arrayRetorno = [];
                                foreach ($productosSinImp as $value) {
                                    $ctrl = false;
                                    foreach ($data as $value2) {
                                        if($value["cod"] == $value2["cod_producto"]){
                                            if(($dsi[0]["nro_articulos_tercero"]-$value2["cantidad"]) <= 0 ){
                                                $arrayRetorno[] = array("cod_producto"=>$value["cod"],"cant_disponible"=>0,"valor_impuesto"=>(double)$value["valor_impuesto"]);
                                            }else{
                                                $arrayRetorno[] = array("cod_producto"=>$value["cod"],"cant_disponible"=>$dsi[0]["nro_articulos_tercero"]-$value2["cantidad"],"valor_impuesto"=>(double)$value["valor_impuesto"]);
                                            }
                                            
                                            $ctrl = true;
                                            break;
                                        }
                                    }
                                    if($ctrl == false){
                                        $arrayRetorno[] = array("cod_producto"=>$value["cod"],"cant_disponible"=>(int)$dsi[0]["nro_articulos_tercero"],"valor_impuesto"=>(double)$value["valor_impuesto"]);
                                    }
                                    
                                }
                                
                                $codigo = 200;
                                $mensaje = "Ok";
                                $retorno = $arrayRetorno;

                            }else{
                                $codigo = 404;
                                $mensaje = "No se encontraron resultados";
                                $retorno = null;
                
                            }
                            
                        }else{
                            $codigo = 400;
                            $mensaje = "parametro productos no tiene los campos requeridos o sus valores son incorrectos";
                            $retorno = [];
                        }
                    }else{
                        $codigo = 404;
                        $mensaje = "No se encontraron resultados";
                        $retorno = null;

                    }
                    
                }else{
                    $codigo = 400;
                    $mensaje = "parametro  productos vacio";
                    $retorno = [];
                }
                
            }else{
                $codigo = 400;
                $mensaje = "parametro productos invalido";
                $retorno = [];
            }
            
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" =>$mensaje,
                "data" =>$retorno
            ));
        } catch (Exception $ex) {
            $codigo = 500;
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" => 'Error en el servidor',
                "data"=>false
            ));
        }
        $this->response->setStatusCode($codigo);
        $this->response->send();
    }


    public function agregarCompraProductos($cod_tercero,$cod_mp,$cod_factura,$params)
    {
        try{
            $compras = json_decode($params["compras"]);
            if($compras){
                if(count($compras) > 0){
                    $sql = "SELECT 
                    dsi.cod
                    FROM descuentos_sin_impuestos dsi
                    where current_timestamp() between CONCAT(dsi.fecha_inicial, ' ', dsi.hora_inicial) and CONCAT(dsi.fecha_final, ' ', dsi.hora_final)";
                    $data = $this->db->fetchAll($sql);
                    if(count($data) > 0){
                        $sql = "SELECT mp.object_id 
                        FROM medios_pagos mp 
                        inner join descuentos_sin_impuesto_mp dsimp on mp.cod = dsimp.cod_medio_pago and dsimp.cod_descuento_sin_impuesto = ".$data[0]["cod"]."
                        where mp.object_id = '".$cod_mp."'";
                        $data = $this->db->fetchAll($sql);
                        if(count($data) > 0){
                            $comprasValidador = [];
                            $ctrl = true;
                            $sql = "select 
                                        p.cod,dsi.cod as cod_dsi
                                    from
                                        descuentos_sin_impuestos dsi
                                        inner join limites_param_fisc lpf on lpf.cod=dsi.cod_lim_pf
                                        inner join detalles_lim_param_fisc dlpf on dlpf.cod_lim_pf =lpf.cod
                                        inner join valores_parametros_fiscales vpf on vpf.cod_parametro_fiscal=lpf.cod
                                        inner join descuentos_sin_impuesto_mp dsimp on dsimp.cod_descuento_sin_impuesto=dsi.cod
                                        inner join productos p on p.cod_categoria=dlpf.cod_categoria
                                        inner join medios_pagos mp on dsimp.cod_medio_pago=mp.cod
                                    where
                                        mp.object_id='".$cod_mp."'
                                        and current_timestamp() between CONCAT(dsi.fecha_inicial, ' ', dsi.hora_inicial) and CONCAT(dsi.fecha_final, ' ', dsi.hora_final)
                                        and p.cod in ";
                            $sqlCodProductos = "(";
                            foreach ($compras as $value) {
                                if(isset($value->cod_producto) && (isset($value->cantidad) && is_numeric($value->cantidad) && $value->cantidad > 0 )){
                                    if (isset($comprasValidador[$value->cod_producto])) {
                                        $comprasValidador[$value->cod_producto]->cantidad = $comprasValidador[$value->cod_producto]->cantidad+$value->cantidad;
                                    }else{
                                        $comprasValidador[$value->cod_producto] = $value;
                                        $sqlCodProductos .= $value->cod_producto.",";
                                    }
                                    
                                }else{
                                    $ctrl = false;
                                    break;
                                }

                            }

                            if($ctrl){
                                $sqlCodProductos = substr($sqlCodProductos,0,-1);
                                $sql = $sql.$sqlCodProductos.")";
                                $productosSinImp = $this->db->fetchAll($sql);
                                if(count($productosSinImp) > 0){
                                    $this->db->begin();
                                    try {
                                        $sqlinsert = "insert into terceros_descuentos_sin_impuestos 
                                        (object_id_tercero, object_id_factura,cod_descuento_sin_impuesto,cod_producto,cantidad)
                                        value ";
                                        foreach ($comprasValidador as $value) {
                                            foreach ($productosSinImp as $value2) {
                                                if($value->cod_producto == $value2["cod"]){
                                                    $sqlinsert.= "('".$cod_tercero."','".$cod_factura."',".$value2["cod_dsi"].",".$value2["cod"].",".$value->cantidad."),";
                                                break;
                                                }
                                            }
                                            
                                        }
                                        $sqlinsert = substr($sqlinsert, 0, -1);
                                        $insert = $this->db->execute($sqlinsert);
                                        if($insert){
                                            $this->db->commit();
                                            $codigo = 200;
                                            $mensaje = "Ok";
                                            $retorno = true;

                                        }else{
                                            $this->db->rollback();
                                            $codigo = 400;
                                            $mensaje = "Error al guardar los datos";
                                            $retorno = false;
                                        }
                                    } catch (Exception $Ex) {
                                        $this->db->rollback();
                                        $codigo = 400;
                                        $mensaje = "Error al guardar los datos";
                                        $retorno = false;
                                    }

                                }else{
                                    $codigo = 404;
                                    $mensaje = "Ningun producto tiene descuento";
                                    $retorno = false;
                    
                                }
                                
                            }else{
                                $codigo = 400;
                                $mensaje = "parametro compra no tiene los campos requeridos o sus valores son incorrectos";
                                $retorno = false;
                            }
                        }else{
                            $codigo = 404;
                            $mensaje = "Este metodo de pago no aplica para el dia sin iva";
                            $retorno = false;
                        }
                    }else{
                        $codigo = 404;
                        $mensaje = "Hoy no es dia sin iva";
                        $retorno = false;
                    }
                
                    
                }else{
                    $codigo = 400;
                    $mensaje = "parametro  compras vacio";
                    $retorno = false;
                }
                
            }else{
                $codigo = 400;
                $mensaje = "parametro compras invalido";
                $retorno = false;
            }
            
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" =>$mensaje,
                "data" =>$retorno
            ));
        } catch (Exception $ex) {
            $codigo = 500;
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" => 'Error en el servidor',
                "data"=>false
            ));
        }
        $this->response->setStatusCode($codigo);
        $this->response->send();
    }

    public function listarProductosSinIva()
    {
        try{
            $sql = "SELECT 
                p.cod as cod_producto,
                p.descripcion as producto,
                i.valor as valor_impuesto
            FROM productos p
                inner join detalles_lim_param_fisc dlf on p.cod_categoria = dlf.cod_categoria
                inner join descuentos_sin_impuestos dsi on dlf.cod_lim_pf = dsi.cod_lim_pf
                inner join impuestos i on p.cod_impuesto = i.cod
            where current_timestamp() between CONCAT(dsi.fecha_inicial, ' ', dsi.hora_inicial) and CONCAT(dsi.fecha_final, ' ', dsi.hora_final);";
            $data = $this->db->fetchAll($sql);

            if(count($data) > 0){
                $dataRetorno = [];
                foreach ($data as $key => $value) {
                    $dataRetorno[] = array("cod_producto"=>$value["cod_producto"],"producto"=>$value["producto"],"valor_impuesto"=>$value["valor_impuesto"],"dsi"=>true);
                }
                $codigo = 200;
                $mensaje = "Ok";
                $retorno = $dataRetorno;
            }else{
                $codigo = 404;
                $mensaje = "No se encontraron resultados";
                $retorno = null;

            }
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" =>$mensaje,
                "data" =>$retorno
            ));
        } catch (Exception $ex) {
            $codigo = 500;
            $this->response->setJsonContent(array(
                "code"=>$codigo,
                "message" => 'Error en el servidor',
                "data"=>false
            ));
        }
        $this->response->setStatusCode($codigo);
        $this->response->send();
        
    }

}