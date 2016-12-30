<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:33
 */

namespace App\MaterialValue;


class MaterialValue
{
    private $id;
    private $name;
    private $baseUnit;
    // TODO: тип
    // TODO: аттрибуты
    // TODO: родитель

    /**
     * MaterialValue constructor.
     * @param $id
     * @param $name
     * @param $baseUnit
     */
    public function __construct($id, $name, $baseUnit)
    {
        $this->id = $id;
        $this->name = $name;
        $this->baseUnit = $baseUnit;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getBaseUnit()
    {
        return $this->baseUnit;
    }


}