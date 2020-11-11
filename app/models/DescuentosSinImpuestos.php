<?php

namespace App\Models;

class DescuentosSinImpuestos extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cod;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var string
     */
    public $fecha_inicial;

    /**
     *
     * @var string
     */
    public $hora_inicial;

    /**
     *
     * @var string
     */
    public $fecha_final;

    /**
     *
     * @var string
     */
    public $hora_final;

    /**
     *
     * @var integer
     */
    public $cod_lim_pf;

    /**
     *
     * @var integer
     */
    public $nro_articulos_tercero;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("dia_sin_iva");
        $this->setSource("descuentos_sin_impuestos");
        $this->hasMany('cod', 'App\Models\DescuentosSinImpuestoMp', 'cod_descuento_sin_impuesto', ['alias' => 'DescuentosSinImpuestoMp']);
        $this->hasMany('cod', 'App\Models\TercerosDescuentosSinImpuestos', 'cod_descuento_sin_impuesto', ['alias' => 'TercerosDescuentosSinImpuestos']);
        $this->belongsTo('cod_lim_pf', 'App\Models\LimitesParamFisc', 'cod', ['alias' => 'LimitesParamFisc']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return DescuentosSinImpuestos[]|DescuentosSinImpuestos|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return DescuentosSinImpuestos|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
