<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 10.01.2017
 * Time: 13:41
 */

namespace App\MaterialValue;


use App\MaterialValue;
use App\Models\AttributeOfMaterialValue;
use App\Models\TypeOfMaterialValue;
use App\Models\Unit;

class Property
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
        $this->model->name = trim($name);
        $this->model->save();
    }

    /**
     * Получить id типа, которому доступен этот аттрибут.
     * Если true - доступно для всех типов
     *
     * @return id|bool
     */
    public function getType()
    {
        if (!$this->model->materialType)
            return true;
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

    public function getUnitName()
    {
        if (!$this->model->unit)
            return false;

        return $this->model->unit->full_name;
    }

    public function getUnitShortName()
    {
        if (!$this->model->unit)
            return false;

        return $this->model->unit->name;
    }

    /**
     * Возвращает установку: фиксированные значения у атрибута,
     * или доступен ввод произвольных
     *
     * true - только фиксированные значения
     * false - доступны произвольные значения
     *
     * @return bool
     */
    public function isFixedValue()
    {
        return $this->model->fixed_value;
    }

    /**
     * @param bool $value
     */
    public function setFixedValue(bool $value)
    {
        $this->model->fixed_value = $value;
        $this->model->save();
    }

    private function loadPossibleValues()
    {
        $this->possibleValues = PropertyPossibleValue::ofAttribute($this->id);
    }

    /**
     * Возвращает массив PropertyPossibleValue c возможными значениями аттрибута
     * @return array|bool
     */
    public function getPossibleValues()
    {
        return $this->possibleValues;
    }

    public function addPossibleValue($value)
    {
        $value = trim($value);
        if (!empty($value)) {
            PropertyPossibleValue::create($this->id, $value);
            $this->loadPossibleValues();
        }
    }

    /**
     * @param $possibleValues array of values
     */
    public function replacePossibleValues($possibleValues)
    {
        foreach ($this->getPossibleValues() as $value) {
            $value->delete();
        }

        foreach ($possibleValues as $value) {
            $this->addPossibleValue($value);
        }

    }

    /**
     * @param $value
     * @return bool
     */
    public function issetPossibleValue($value)
    {
        $value = mb_strtolower($value);
        foreach ($this->possibleValues as $possibleValue) {
            $possibleValue = mb_strtolower($possibleValue->value);

            if ($possibleValue == $value)
                return true;
        }
        return false;
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

    public function trashed()
    {
        return $this->model->trashed();
    }


    /**
     * @param $name
     * @param boolean $fixedValue
     * @param int $type_material
     * @param int $unit
     * @param array $values
     * @return Property
     */
    public static function create($name, $fixedValue, $type_material = null, $unit = null, $values = array())
    {

        $attribute = new AttributeOfMaterialValue();
        $attribute->name = trim($name);
        $attribute->fixed_value = $fixedValue;

        if ($type_material)
            $attribute->materialType()->associate(TypeOfMaterialValue::find($type_material));
        if ($unit)
            $attribute->unit()->associate(Unit::find($unit));

        $attribute->save();

        $new_attribute = new self($attribute);

        if ($values) {
            foreach ($values as $value) {
                $new_attribute->addPossibleValue($value);
            }
        }

        return $new_attribute;
    }

    /**
     * @param $id
     * @return Property|bool
     */
    public static function find($id)
    {
        $property = AttributeOfMaterialValue::property($id)->withTrashed()->first();

        if ($property)
            return new self($property);
        else
            return false;
    }

    public static function all()
    {
        $properties = AttributeOfMaterialValue::properties()->withTrashed()->orderBy('name')->get();

        if ($properties) {
            $objects = array();
            foreach ($properties as $property) {
                $objects[] = new self($property);
            }
            return $objects;
        } else {
            return false;
        }
    }

    public function destroy()
    {

        if (count($this->model->materials) > 0) {
            $this->model->delete();
        } else {
            $this->model->forceDelete();
        }

    }

    public function restore()
    {
        $this->model->restore();
    }
    //TODO: destroy (поиск использования, если не используется, произвести удаление, если исп. мягк удалить)
}