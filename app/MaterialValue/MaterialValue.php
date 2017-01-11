<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:33
 */

namespace App\MaterialValue;


use App\AttributeOfMaterialValue;
use App\TypeOfMaterialValue;
use App\Unit;

class MaterialValue
{
    protected $model;
    protected $specifics;
    protected $abstract;

    /**
     * MaterialValue constructor.
     * @param \App\MaterialValue $model
     */
    public function __construct(\App\MaterialValue $model)
    {
        $this->model = $model;
        $this->loadSpecifics();
        $this->loadAbstraction();
    }

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    /**
     * @return id
     */
    public function getId()
    {
        return $this->model->id;
    }

    /**
     * @return name
     */
    public function getName()
    {
        return $this->model->name;
    }
    public function setName($name)
    {
        $this->model->name = $name;
    }

    /**
     * @return id
     */
    public function getUnit()
    {
        return $this->model->unit_id;
    }

    /**
     * @param boolean $short_name
     * @return unit_name
     */
    public function getUnitName($short_name = false)
    {
        if ($short_name)
            return $this->model->unit->name;

        return $this->model->unit->full_name;
    }

    private function loadAbstraction()
    {
        $this->abstract = false;

        if ($this->model->parent)
            $this->abstract = new MaterialValue($this->model->parent);
    }

    /**
     * @return MaterialValue|bool
     */
    public function getAbstraction()
    {
        return $this->abstract;
    }

    private function loadSpecifics()
    {
        $this->specifics = [];

        foreach ($this->model->children as $child) {
            $this->specifics[] = new MaterialValue($child);
        }
    }

    /**
     * @return array of App\MaterialValue\MaterialValue
     */
    public function getSpecifics()
    {
        return $this->specifics;
    }

    public function getAttributes()
    {
        return $this->model->attributes;
    }

    public function getType()
    {
        return $this->model->type_id;
    }

    public function getTypeName()
    {
        return $this->model->type->name;
    }

    public static function create($name, $type_id, $baseUnit_id)
    {
        $type = TypeOfMaterialValue::find($type_id);
        $baseUnit = Unit::find($baseUnit_id);

        $material = new \App\MaterialValue();
        $material->name = $name;
        $material->type()->associate($type);
        $material->unit()->associate($baseUnit);

        $material->save();

        return new MaterialValue($material);

    }

    public static function find($id)
    {
        $material = \App\MaterialValue::find($id);

        if ($material)
            return new self($material);
        else
            return false;
    }

    public function setAttribute($attribute_id, $value)
    {
        $attribute = AttributeOfMaterialValue::find($attribute_id);

        if ($attribute->materialType == $this->model->type || !$attribute->materialType) {
            $this->model->attributes()->attach($attribute->id, ['value' => $value]);
        }
    }
    //TODO: setSpecific

    //TODO: delete


}