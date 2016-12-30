<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 17:31
 */

namespace App\Collections;


use App\Interfaces\IRegisterString;

class WarehouseCollection extends Collection
{
    /**
     * WarehouseCollection constructor.
     */
    public function __construct()
    {
        $this->typeItem = IRegisterString::class;
    }
}