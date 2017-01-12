<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:33
 */

namespace App\MaterialValue;


use App\Models\AttributeOfMaterialValue;
use App\Models\MaterialValue;
use App\Models\TypeOfMaterialValue;
use App\Models\Unit;

/**
 * Материальная ценность.
 * Класс, для создания материальной ценности(далее материал), а так же
 * для получения и редактирования информации сущестсвующей.
 * @package App\MaterialValue
 */
class Material
{

    /**
     * Модель доступа к БД
     * @var MaterialValue
     */
    protected $model;

    /**
     * Материалы, конкретизирующие данный элемент
     *
     * @var \App\MaterialValue\Material in array
     *
     */
    protected $specifics;

    /**
     * Материал, являющийся абстракцией для данного элемента
     * @var \App\MaterialValue\Material
     */
    protected $abstract;

    protected $attributes;

    /**
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        $this->model = $model;
        $this->loadSpecifics();
        $this->loadAbstraction();
        $this->loadAttributes();
    }

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    /**
     * Получить идентификатор
     * @return integer id мат. ценности
     */
    public function getId()
    {
        return $this->model->id;
    }

    /**
     * Получиить наименование
     * @return string Наименование мат. ценности
     */
    public function getName()
    {
        return $this->model->name;
    }

    /**
     * Изменить наименование мат. ценности
     * @param string $name Новое наименование
     */
    public function setName($name)
    {
        $this->model->name = $name;
    }

    /**
     * Получить идентификатор единицы измерения
     * @return integer id единицы измерения
     */
    public function getUnit()
    {
        return $this->model->unit_id;
    }

    /**
     * Получить наименование единицы измерения
     * @param boolean $short_name Сокращенное наименование
     * @return string Наименование единицы измерения
     */
    public function getUnitName($short_name = false)
    {
        if ($short_name)
            return $this->model->unit->name;

        return $this->model->unit->full_name;
    }

    /**
     * Обновляет информацию об абстракции данного элемента
     */
    private function loadAbstraction()
    {
        $this->abstract = false;

        if ($this->model->parent)
            $this->abstract = new Material($this->model->parent);
    }

    /**
     * Получить элемент, абстрагирующий данный
     * @return Material|bool
     */
    public function getAbstraction()
    {
        return $this->abstract;
    }

    /**
     * Обновляет информацию о конкретизирующих элементах
     */
    private function loadSpecifics()
    {
        $this->specifics = [];

        foreach ($this->model->children as $child) {
            $this->specifics[] = new Material($child);
        }
    }

    /**
     * Получить массив элементов, состоящий из элементов, конкретизирующий данный
     * @return array состоящий из App\MaterialValue\MaterialValue
     */
    public function getSpecifics()
    {
        return $this->specifics;
    }

    private function loadAttributes()
    {
        $this->attributes = [];

        foreach ($this->model->attributes as $attribute) {
            $this->attributes[$attribute->id] = new AttributeValue($attribute);

        }
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getType()
    {
        return $this->model->type_id;
    }

    public function getTypeName()
    {
        return $this->model->type->name;
    }

    /**
     * Создает новый материал, и передает его объект
     * @param string $name
     * @param integer $type_id
     * @param integer $baseUnit_id
     * @return Material
     */
    public static function create($name, $type_id, $baseUnit_id)
    {
        $type = TypeOfMaterialValue::find($type_id);
        $baseUnit = Unit::find($baseUnit_id);

        $material = new MaterialValue();
        $material->name = $name;
        $material->type()->associate($type);
        $material->unit()->associate($baseUnit);

        $material->save();

        return new Material($material);

    }

    /**
     * Ищет материал по его id и возвращает его объект, если есть
     * @param integer $id
     * @return Material|bool
     */
    public static function find($id)
    {
        $material = MaterialValue::find($id);

        if ($material)
            return new self($material);
        else
            return false;
    }

    /**
     * Устанавливает материалу значение атрибута
     * Если у элемента уже установлен такой атрибут, то его значение будет перезаписано
     * @param integer $attribute_id
     * @param string|int|float $value
     */
    public function setAttribute($attribute_id, $value)
    {
        if ($this->issetAttribute($attribute_id)) {
            $this->attributes[$attribute_id]->setValue($value);
        } else {
            $attribute = AttributeOfMaterialValue::find($attribute_id);

            if ($attribute->materialType == $this->model->type || !$attribute->materialType) {
                $this->model->attributes()->attach($attribute->id, ['value' => $value]);
            }
        }
    }

    /**
     * Удаляет у материала атрибут
     * @param integer $attribute_id
     * @return void
     */
    public function removeAttribute($attribute_id)
    {
        if ($this->issetAttribute($attribute_id)) {
            $this->model->attributes()->detach($attribute_id);
            $this->loadAttributes();
        }
    }

    /**
     * Проверяет, имеет ли материал запрошенный атрибут
     * @param integer $id
     * @return bool
     */
    public function issetAttribute($id)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->id == $id)
                return true;
        }
        return false;
    }

    //TODO: setSpecific
    //TODO: delete


}