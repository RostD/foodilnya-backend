<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 17:44
 */

namespace App\Warehouse\AcceptPolicy\Take;


class FreeTake extends TakeBase
{

    public function canTake()
    {
        return true;
    }
}