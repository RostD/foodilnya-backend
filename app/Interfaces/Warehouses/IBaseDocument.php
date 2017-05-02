<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 02.05.2017
 * Time: 11:14
 */

namespace App\Interfaces\Warehouses;


use App\Interfaces\IMaterialDocument;

interface IBaseDocument extends IMaterialDocument
{
    public function IsComingWHBase();
}