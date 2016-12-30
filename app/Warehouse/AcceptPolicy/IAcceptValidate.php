<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:24
 */

namespace App\Warehouse\AcceptPolicy;


interface IAcceptValidate
{
    public function isValidData();
}