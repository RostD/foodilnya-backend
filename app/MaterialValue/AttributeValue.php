<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 12.01.2017
 * Time: 11:49
 */

namespace App\MaterialValue;

use App\Models\AttributeOfMaterialValue;

class AttributeValue
{


    private $id;
    private $name;
    private $unitId = false;
    private $unitName = false;
    private $unitShortName = false;
    private $value;
    protected $model;

    /**
     * AttributeValue constructor.
     * @param AttributeOfMaterialValue $attribute
     */
    public function __construct(AttributeOfMaterialValue $attribute)
    {

        $this->id = $attribute->id;
        $this->name = $attribute->name;
        $this->value = $attribute->pivot->value;

        if ($attribute->unit) {
            $this->unitId = $attribute->unit->id;
            $this->unitName = $attribute->unit->full_name;
            $this->unitShortName = $attribute->unit->name;
        }

        $this->model = $attribute;

    }

    /**
     * @return mixed
     */
    public function getUnitName($short = false)
    {
        if ($short)
            return $this->unitShortName;
        return $this->unitName;
    }

    function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
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
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->model->pivot->value = $value;
        $this->model->pivot->save();
    }


}