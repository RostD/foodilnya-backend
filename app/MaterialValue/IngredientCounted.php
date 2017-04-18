<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 10.04.2017
 * Time: 11:04
 */

namespace App\MaterialValue;


use App\Interfaces\Counted;

class IngredientCounted extends Ingredient implements Counted
{
    protected $quantity = null;
    protected $pivotId = null;

    /**
     * @return null
     */
    public function getPivotId()
    {
        return $this->pivotId;
    }

    /**
     * @param null $pivotId
     */
    public function setPivotId($pivotId)
    {
        $this->pivotId = $pivotId;
        //TODO
    }

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
        //TODO
    }


}