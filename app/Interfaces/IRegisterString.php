<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 17:26
 */

namespace App\Interfaces;


interface IRegisterString
{
    public function getName();

    public function getQuantity();

    public function getUnit();
}