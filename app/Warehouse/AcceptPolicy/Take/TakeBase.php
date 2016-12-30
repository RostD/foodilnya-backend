<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:35
 */

namespace App\Warehouse\AcceptPolicy\Take;


use App\Warehouse\AcceptPolicy\IAcceptTake;
use App\Warehouse\IWarehouse;

abstract class TakeBase implements IAcceptTake
{
    protected $warehouse;

    /**
     * TakeBase constructor.
     * @param $warehouse IWarehouse
     */
    public function __construct(IWarehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }
}