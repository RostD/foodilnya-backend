<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 17:45
 */

namespace App\Warehouse\AcceptPolicy\Validate;


class NoValidate extends ValidateBase
{

    public function isValidData()
    {
        return true;
    }
}