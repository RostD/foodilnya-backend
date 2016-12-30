<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:07
 */

namespace App\Warehouse\AcceptPolicy;


interface IAcceptPut
{
    public function canPut();
}