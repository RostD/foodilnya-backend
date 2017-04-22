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

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($value)
    {
        $this->quantity = $value;
    }


}