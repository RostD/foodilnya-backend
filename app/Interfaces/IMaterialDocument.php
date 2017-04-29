<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 29.04.2017
 * Time: 8:41
 */

namespace App\Interfaces;


use App\Collections\WarehouseCollection;

interface IMaterialDocument
{

    /**
     * @return bool
     */
    public function isComing();

    /**
     * @return WarehouseCollection
     */
    public function getMaterialValuesData();

    /**
     * @return string
     */
    public function getDocumentName();
}