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
 * Класс применяется, когда необходимо передавать количество материальных ценностей.
 * @package App\MaterialValue
 */
abstract class CountedMaterial extends Material implements IRegisterString
{
    private $quantity;
    //private $unit;

    /**
     * CountedMaterialValue constructor.
     * @param integer $id Сушествующий идентификатор
     * @param string $name
     * @param integer $baseUnit_id
     * @param float $quantity
     */
    public function __construct($id, $name, $baseUnit_id, $quantity)
    {
        //TODO: Изменить класс, в соответствии с конструктором родительского класса
        parent::__construct($id, $name, $baseUnit_id);
        $this->quantity = $quantity;
        //$this->unit = $unit;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return integer id
     */
    public function getUnit()
    {
        return $this->unit;
    }


}