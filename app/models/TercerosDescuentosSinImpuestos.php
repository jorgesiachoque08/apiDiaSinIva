<?php

namespace App\Models;

class TercerosDescuentosSinImpuestos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $object_id_tercero;

    /**
     *
     * @var string
     */
    public $object_id_factura;

    /**
     *
     * @var integer
     */
    public $cod_descuento_sin_impuesto;

    /**
     *
     * @var integer
     */
    public $cod_producto;

    /**
     *
     * @var integer
     */
    public $cantidad;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("dia_sin_iva");
        $this->setSource("terceros_descuentos_sin_impuestos");
        $this->belongsTo('cod_descuento_sin_impuesto', 'App\Models\DescuentosSinImpuestos', 'cod', ['alias' => 'DescuentosSinImpuestos']);
        $this->belongsTo('cod_producto', 'App\Models\Productos', 'cod', ['alias' => 'Productos']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TercerosDescuentosSinImpuestos[]|TercerosDescuentosSinImpuestos|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TercerosDescuentosSinImpuestos|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
