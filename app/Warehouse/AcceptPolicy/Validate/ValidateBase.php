<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:37
 */

namespace App\Warehouse\AcceptPolicy\Validate;


use App\Warehouse\AcceptPolicy\IAcceptValidate;
use App\Warehouse\IWarehouse;

abstract class ValidateBase implements IAcceptValidate
{
    protected $warehouse;

    /**
     * ValidateBase constructor.
     * @param $warehouse IWarehouse
     */
    public function __construct(IWarehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }
}