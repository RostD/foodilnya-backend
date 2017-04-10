<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 10.04.2017
 * Time: 11:32
 */

namespace App\Interfaces;


interface Counted
{
    public function getQuantity();

    public function setQuantity($quantity);
}