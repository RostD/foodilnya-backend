<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:40
 */

namespace App\MaterialValue;

use App\Interfaces\IRegisterString;


/**
 * Class CountedMaterialValue
 * @package App\MaterialValue
 *
 * Класс применяется, когда необходимо передавать количество материальных ценностей.
 */
class CountedMaterialValue extends MaterialValue implements IRegisterString
{
    private $quantity;
    //private $unit;

    /**
     * CountedMaterialValue constructor.
     * @param $id
     * @param $name
     * @param $baseUnit
     * @param $quantity
     */
    public function __construct($id, $name, $baseUnit, $quantity)
    {
        parent::__construct($id, $name, $baseUnit);
        $this->quantity = $quantity;
        //$this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }


}