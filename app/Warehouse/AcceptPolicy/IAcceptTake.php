<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 13:06
 */

namespace App\Warehouse\AcceptPolicy;


interface IAcceptTake
{
    public function canTake();
}