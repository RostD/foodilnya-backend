<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:33
 */

namespace App\MaterialValue;


use App\Http\Helpers\Notifications;
use App\Models\AttributeOfMaterialValue;
use App\Models\MaterialValue;
use App\Models\TypeOfMaterialValue;
use App\Models\Unit;

/**
 * Материальная ценность.
 * Класс, для создания материальной ценности(далее материал), а так же
 * для получения и редактирования информации сущестсвующей.
 * @property  typeName
 * @property  id
 * @package App\MaterialValue
 */
abstract class Material
{

    /**
     * Модель доступа к БД
     * @var MaterialValue
     */
    protected $model;


    /**
     * Массив атрибутов материала
     * @var PropertyValue в массиве
     */
    protected $properties = [];
    protected $prop_loaded = false;

    /**
     * Массив тегов материала
     * @var PropertyValue в массиве
     */
    protected $tags = [];
    protected $tags_loaded = false;


    /**
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        $this->model = $model;
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

    public function __set($name, $value)
    {
        $name = "set" . $name;
        return $this->$name($value);
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
        $name = trim($name);
        if (!empty($name)) {
            $this->model->name = $name;
            $this->model->save();
        }        
    }

    public function getDescription()
    {
        return $this->model->description;
    }

    public function setDescription($text)
    {
        $text = trim($text);
        if (!empty($text)) {
            $this->model->description = $text;
            $this->model->save();
        }
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
     * @return string Наименование единицы измерения
     */
    public function getUnitName()
    {
        return $this->model->unit->full_name;
    }

    public function getUnitShortName()
    {
        return $this->model->unit->name;
    }

    private function loadProperties()
    {
        if (!$this->prop_loaded) {
            $this->properties = [];
            foreach ($this->model->properties()->get() as $p) {

                $this->properties[$p->id] = new PropertyValue($p);
            }

            $this->prop_loaded = true;
        }

    }    

    /**
     * Возвращает массив аттрибутов в виде объектов
     * @see AttributeValue
     * @return array
     */
    public function getProperties()
    {
        $this->loadProperties();
        return $this->properties;
    }

    private function loadTags()
    {
        if (!$this->tags_loaded) {
            $this->tags = [];
            foreach ($this->model->tags()->get() as $t) {
                $this->tags[$t->id] = new Tag($t);
            }
            $this->tags_loaded = true;
        }
    }
    /**
     * Возвращает массив аттрибутов в виде объектов
     * @see AttributeValue
     * @return array
     */
    public function getTags()
    {
        $this->loadTags();
        return $this->tags;
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

    public function addTag($tag_id)
    {
        if (!$this->issetTag($tag_id)) {
            $tag = AttributeOfMaterialValue::tag($tag_id)->first();
            $this->model->attributes()->attach($tag->id);

            $this->tags_loaded = false;
        }
    }

    public function removeTag($tag_id)
    {
        if ($this->issetTag($tag_id)) {
            $this->model->attributes()->detach($tag_id);
            $this->tags_loaded = false;
        }
    }

    /**
     * @param array $newTags array of id's
     */
    public function replaceTags(array $newTags)
    {
        foreach ($this->getTags() as $tag) {
            $this->removeTag($tag->id);
        }

        foreach ($newTags as $newTag) {
            $this->addTag($newTag);
        }
    }

    public function issetTag($tag_id)
    {
        $this->loadTags();
        foreach ($this->tags as $tag) {
            if ($tag->id == $tag_id)
                return true;
        }
        return false;
    }
    /**
     * Устанавливает материалу значение атрибута
     * Если у элемента уже установлен такой атрибут, то его значение будет перезаписано
     * @param integer $prop_id
     * @param string|int|float $value
     */
    public function setProperty($prop_id, $value)
    {
        if (!$value) {
            Notifications::add('Пустое значение свойства не принимается', 2, __FILE__ . ' line:' . __LINE__);
            return;
        }

        if ($this->issetProperty($prop_id)) {
            $this->properties[$prop_id]->setValue($value);
        } else {
            $property = AttributeOfMaterialValue::property($prop_id)->first();

            if ($property->materialType == $this->model->type || !$property->materialType) {
                $this->model->attributes()->attach($property->id, ['value' => $value]);
            }
        }

        $this->prop_loaded = false;
    }

    /**
     * Удаляет у материала атрибут
     * @param integer $attribute_id
     * @return void
     */
    public function removeProperty($attribute_id)
    {
        if ($this->issetProperty($attribute_id)) {
            $this->model->attributes()->detach($attribute_id);
            $this->prop_loaded = false;
        }
    }

    /**
     * Проверяет, имеет ли материал запрошенный атрибут
     * @param integer $property_id
     * @return bool
     */
    public function issetProperty($property_id)
    {
        $this->loadProperties();
        foreach ($this->properties as $prop) {
            if ($prop->id == $property_id)
                return true;
        }
        return false;
    }

    public function trashed()
    {
        return $this->model->trashed();
    }
    
    public function toArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->typeName,
            'type_id' => $this->type,
            'unit' => $this->unitName,
            'unit_id' => $this->unit,
            'properties' => [],
            'tags' => [],
        ];

        if (!empty($this->getProperties())) {
            foreach ($this->getProperties() as $a) {
                $array['properties'][] = $a->toArray();
            }
        }

        if (!empty($this->getTags())) {
            foreach ($this->getTags() as $t) {
                $array['tags'][] = $t->toArray();
            }
        }

        return $array;
    }

    /**
     * Создает новый материал, и возвращает его модель
     * @param string $name
     * @param integer $type_id
     * @param integer $baseUnit_id
     * @return MaterialValue
     */
    protected static function createMaterial($name, $type_id, $baseUnit_id)
    {
        $type = TypeOfMaterialValue::find($type_id);
        $baseUnit = Unit::find($baseUnit_id);

        $material = new MaterialValue();
        $material->name = $name;
        $material->type()->associate($type);
        $material->unit()->associate($baseUnit);
        $material->save();
        return $material;


    }

    public static function initial($material, $model)
    {
        return new $material($model);
    }

    /**
     * Ищет материал по его id и возвращает его модель
     * @param integer $id
     * @return MaterialValue|bool
     */
    abstract public static function find($id);

}