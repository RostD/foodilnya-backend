<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 10.04.2017
 * Time: 11:22
 */

namespace App\MaterialValue;


use App\Interfaces\Counted;

class AdaptationCounted extends Adaptation implements Counted
{

    protected $quantity = null;

    /**
     * @return null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param null $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
}