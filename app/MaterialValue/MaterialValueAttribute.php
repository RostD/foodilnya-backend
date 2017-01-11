<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 10.01.2017
 * Time: 13:41
 */

namespace App\MaterialValue;


use App\AttributeOfMaterialValue;
use App\MaterialValue;
use App\TypeOfMaterialValue;
use App\Unit;

class MaterialValueAttribute
{
    protected $model;
    protected $possibleValues;

    /**
     * MaterialValueAttribute constructor.
     * @param $model
     */
    public function __construct(AttributeOfMaterialValue $model)
    {
        $this->model = $model;
        $this->loadPossibleValues();
    }

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    public function getId()
    {
        return $this->model->id;
    }

    public function getName()
    {
        return $this->model->name;
    }

    public function setName($name)
    {
        $this->model->name = $name;
        $this->model->save();
    }

    /**
     * Получить id типа, которому доступен этот аттрибут.
     * Если false - доступно для всех типов
     *
     * @return id|bool
     */
    public function getType()
    {
        if (!$this->model->materialType)
            return false;
        return $this->model->materialType->id;
    }

    public function getTypeName()
    {
        if (!$this->model->materialType)
            return false;

        return $this->model->materialType->name;
    }

    /**
     * Получить id единицы измерения аттрибута
     * Если false - нет единицы измерения
     *
     * @return id|bool
     */
    public function getUnit()
    {
        if (!$this->model->unit)
            return false;

        return $this->model->unit->id;
    }

    public function getUnitName($short = false)
    {
        if (!$this->model->unit)
            return false;

        if ($short)
            return $this->model->unit->name;
        else
            return $this->model->unit->full_name;
    }

    /**
     * true - Аттрибут имеет только фиксированные значения
     * false - доступны произвольные значения
     *
     * @return bool
     */
    public function getFixedValue()
    {
        return $this->model->fixed_value;
    }

    private function loadPossibleValues()
    {
        $this->possibleValues = AttributePossibleValue::ofAttribute($this->id);
    }

    /**
     * Возвращает массив AttributePossibleValue c возможными значениями аттрибута
     * @return array|bool
     */
    public function getPossibleValues()
    {
        return $this->possibleValues;
    }

    public function addPossibleValue($value)
    {
        AttributePossibleValue::create($this->id, $value);
        $this->loadPossibleValues();
    }

    /**
     * @param $id
     */
    public function removePossibleValue($id)
    {
        if ($this->possibleValues) {
            foreach ($this->possibleValues as $value) {
                if ($value->id == $id) {
                    $value->delete();
                    break;
                }
            }

            $this->loadPossibleValues();
        }
    }


    /**
     * @param $name
     * @param boolean $fixedValues
     * @param null $type_material
     * @param null $unit
     * @param array $values
     * @return MaterialValueAttribute
     */
    public static function create($name, $fixedValues, $type_material = null, $unit = null, $values = array())
    {

        $attribute = new AttributeOfMaterialValue();
        $attribute->name = $name;
        $attribute->fixed_value = $fixedValues;

        if ($type_material)
            $attribute->materialType()->associate(TypeOfMaterialValue::find($type_material));
        if ($unit)
            $attribute->unit()->associate(Unit::find($unit));

        $attribute->save();

        $new_attribute = new self($attribute);

        if ($values) {
            foreach ($values as $value) {
                $new_attribute->setPossibleValue($value);
            }
        }

        return $new_attribute;
    }

    /**
     * @param $id
     * @return MaterialValueAttribute|bool
     */
    public static function find($id)
    {
        $material = AttributeOfMaterialValue::find($id);

        if ($material)
            return new self($material);
        else
            return false;
    }

    //TODO: delete (поиск использования, если не используется, произвести удаление, если исп. мягк удалить)
}