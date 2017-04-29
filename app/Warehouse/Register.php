<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 16:29
 */

namespace App\Warehouse;


use App\Interfaces\IRegisterString;
use App\Models\WarehouseRegister;



class Register
{
    /**
     * Производит запись в регистр о движении материальной ценности
     * @param integer $warehouse_id id склада
     * @param string $documentName
     * @param boolean $isComing Приход(true) или расход(false)
     * @param IRegisterString $material
     */
    static public function record($warehouse_id, string $documentName, bool $isComing, IRegisterString $material)
    {
        $item = new WarehouseRegister();
        $item->warehouse_id = $warehouse_id;
        $item->material_id = $material->getMaterialId();
        $item->coming = $isComing;
        $item->document = $documentName;
        $item->quantity = (float)$material->getQuantity();

        $item->save();
    }

    static public function refutation($warehouse_id, string $documentName)
    {
        $strings = WarehouseRegister::where([['warehouse_id', $warehouse_id], ['document', $documentName]])->get();

        if ($strings)
            foreach ($strings as $string)
                $string->delete();
    }
}