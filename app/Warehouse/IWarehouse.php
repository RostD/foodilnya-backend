<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:11
 */

namespace App\Warehouse;


use App\Collections\WarehouseCollection;

interface IWarehouse
{
    public function put(WarehouseCollection $data);

    public function take(WarehouseCollection $data);

    public function getRequestedData();
}