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
     * @var Material в массиве
     *
     */
    protected $specifics;

    /**
     * Материал, являющийся абстракцией для данного элемента
     * @var Material
     */
    protected $abstract;

    /**
     * Массив атрибутов материала
     * @var AttributeValue в массиве
     */
    protected $attributes;

    /**
     * @param MaterialValue $model
     */
    protected function __construct(MaterialValue $model)
    {
        $this->model = $model;
        $this->loadSpecifics();
        $this->loadAbstraction();
        $this->loadAttributes();
    }

    /**
     * Магический метод, позволяющий обращатся к методам объекта, как к свойствам
     *
     * Например, вызов метода $obj->getName() может быть заменен на $obj->name.
     * Относится к методам без параметров
     */
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
     * Обновляет информацию об абстрактном материале
     * @return void
     */
    private function loadAbstraction()
    {
        $this->abstract = false;

        if ($this->model->parent)
            $this->abstract = new Material($this->model->parent);
    }

    /**
     * Получить материал, являющийся абстракцией этого материала
     * @return Material|bool
     */
    public function getAbstraction()
    {
        return $this->abstract;
    }

    /**
     * Загружает конкретизирующие материалы
     * @return void
     */
    private function loadSpecifics()
    {
        $this->specifics = [];

        foreach ($this->model->children as $child) {
            $this->specifics[] = new Material($child);
        }
    }

    /**
     * Получить материалы, конкретизирующий этот материал
     * @return Material в массиве
     */
    public function getSpecifics()
    {
        return $this->specifics;
    }

    /**
     * Загружает аттрибуты материала и их значения
     * @return void
     */
    private function loadAttributes()
    {
        $this->attributes = [];

        foreach ($this->model->attributes as $attribute) {
            $this->attributes[$attribute->id] = new AttributeValue($attribute);

        }
    }

    /**
     * Возвращает массив аттрибутов в виде объектов
     * @see AttributeValue
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Возвращает id типа материала
     * @return int
     */
    public function getType()
    {
        return $this->model->type_id;
    }

    /**
     * Возвращает наименование типа материалы
     * @return string
     */
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
    public function setAttribute($attribute_id, $value = null)
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
     * @param integer $attribute_id
     * @return bool
     */
    public function issetAttribute($attribute_id)
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->id == $attribute_id)
                return true;
        }
        return false;
    }

    //TODO: setSpecific
    //TODO: delete


}