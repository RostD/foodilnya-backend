<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 16:29
 */

namespace App\Warehouse;


use App\Interfaces\IRegisterString;
use App\WarehouseRegister;


class Register
{
    static public function record($warehouse_id, bool $isComing, IRegisterString $material)
    {
        $item = new WarehouseRegister();
        $item->warehouse_id = $warehouse_id;
        $item->material = $material->getName();
        $item->coming = $isComing;
        $item->quantity = $material->getQuantity();

        $item->save();
    }
}