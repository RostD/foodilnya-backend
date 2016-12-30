<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 17:43
 */

namespace App\Warehouse\AcceptPolicy\Put;


class FreePut extends PutBase
{

    public function canPut()
    {
        return true;
    }
}