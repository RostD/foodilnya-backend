<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 12.01.2017
 * Time: 11:49
 */

namespace App\MaterialValue;

use App\Models\AttributeOfMaterialValue;

/**
 * Значеине атрибута
 *
 * Класс предназначен для удобного доступа к установленному атрибуту материала.
 * Позволяет легко изменять значение атрибута
 * @package App\MaterialValue
 */
class PropertyValue
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

    /**
     * @param string|int|float $value
     */
    public function setValue($value)
    {
        $attribute = Property::find($this->id);        

        if (($attribute->isFixedValue() && $attribute->issetPossibleValue($value)) ||
            !$attribute->isFixedValue()
        ) {
            $this->model->pivot->value = $value;
            $this->model->pivot->save();
        }
        
    }

    public function toArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'unit' => $this->unitName,
            'unit_id' => $this->unitId,
            'value' => $this->value
        ];

        return $array;
    }


}