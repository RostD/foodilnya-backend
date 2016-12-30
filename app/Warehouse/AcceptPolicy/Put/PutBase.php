<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:32
 */

namespace App\Warehouse\AcceptPolicy\Put;


use App\Warehouse\AcceptPolicy\IAcceptPut;
use App\Warehouse\IWarehouse;

abstract class PutBase implements IAcceptPut
{
    protected $warehouse;

    /**
     * PutBase constructor.
     * @param $warehouse IWarehouse
     */
    public function __construct(IWarehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }
}