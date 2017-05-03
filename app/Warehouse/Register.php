<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 16:29
 */

namespace App\Warehouse;


use App\Interfaces\IRegisterString;
use App\Models\MaterialValue;
use App\Models\WarehouseRegister;
use App\Models\WarehouseStatus;
use App\Report\ReportMaterialString;
use Illuminate\Support\Facades\DB;


class Register
{
    private static $transactionStarted = false;

    static public function beginTransaction()
    {
        DB::beginTransaction();
        self::$transactionStarted = true;
    }

    static public function commitTransaction()
    {
        DB::commit();
        self::$transactionStarted = false;
    }

    static public function recountBalance($warehouseId)
    {

        $allMaterials = WarehouseRegister::select('material_id')->where('warehouse_id', $warehouseId)->groupBy('material_id')->get();

        foreach ($allMaterials as $material) {
            $strings = WarehouseRegister::where([['warehouse_id', $warehouseId], ['material_id', $material->material_id]])->get();

            $quantity = (float)0;
            foreach ($strings as $string) {
                if ($string->coming)
                    $quantity += (float)$string->quantity;
                else
                    $quantity -= (float)$string->quantity;
            }

            $balance = WarehouseStatus::where([['warehouse_id', $warehouseId], ['material_id', $material->material_id]])->first();

            if (!$balance) {
                $balance = new WarehouseStatus();
                $balance->warehouse_id = $warehouseId;
                $balance->material_id = $material->material_id;
            }

            $balance->quantity = $quantity;
            $balance->save();

        }


    }
    /**
     * Производит запись в регистр о движении материальной ценности
     * @param integer $warehouse_id id склада
     * @param string $documentName
     * @param boolean $isComing Приход(true) или расход(false)
     * @param IRegisterString $material
     */
    static public function record($warehouse_id, string $documentName, bool $isComing, IRegisterString $material)
    {
        if (!self::$transactionStarted)
            return;

        $item = new WarehouseRegister();
        $item->warehouse_id = $warehouse_id;
        $item->material_id = $material->getMaterialId();
        $item->coming = $isComing;
        $item->document = $documentName;
        $item->quantity = (float)$material->getQuantity();

        $item->save();

        self::setBalance($warehouse_id, $material->getMaterialId(), (float)$material->getQuantity(), $isComing);
    }

    static public function refutation($warehouse_id, string $documentName)
    {
        if (!self::$transactionStarted)
            return;
        
        $strings = WarehouseRegister::where([['warehouse_id', $warehouse_id], ['document', $documentName]])->get();

        if ($strings)
            foreach ($strings as $string) {
                self::setBalance($warehouse_id, $string->material_id, $string->quantity, !$string->coming);
                $string->delete();
            }


    }

    static private function setBalance($wh_id, $material_id, float $quantity, bool $isComing)
    {
        $balance = WarehouseStatus::where([['warehouse_id', $wh_id], ['material_id', $material_id]])->first();

        if (!$balance) {
            $balance = new WarehouseStatus();
            $balance->warehouse_id = $wh_id;
            $balance->material_id = $material_id;
        }

        if ($isComing)
            $balance->quantity = (float)$balance->quantity + $quantity;
        else
            $balance->quantity = (float)$balance->quantity - $quantity;

        $balance->save();
    }

    static public function getBalance($wh_id)
    {
        $balance = WarehouseStatus::where('warehouse_id', $wh_id)->get();
        $materials = [];

        foreach ($balance as $item) {
            $material = MaterialValue::find($item->material_id);
            if ($material)
                $materials[] = new ReportMaterialString($material, (float)$item->quantity);
        }

        return $materials;
    }

    /**
     * @param $wh_id
     * @param $material_id
     * @return float
     */
    static public function getMaterialBalance($wh_id, $material_id)
    {
        $string = WarehouseStatus::where('warehouse_id', $wh_id)
            ->where('material_id', $material_id)->first();
        if ($string) {
            $material = MaterialValue::find($string->material_id);
            if ($material)
                return (float)$string->quantity;
        }

        return (float)0;
    }
}