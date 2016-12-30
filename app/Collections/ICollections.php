<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:49
 */

namespace App\Collections;


interface ICollections
{
    public function add($item);

    public function remove($item);

    public function array();

    public function getType();
}