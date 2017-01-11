<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 11.01.2017
 * Time: 8:29
 */

namespace App\MaterialValue;


use App\PossibleAttributeValue;

class AttributePossibleValue
{
    private $model;

    public function __construct(PossibleAttributeValue $model)
    {
        $this->model = $model;
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

    public function getValue()
    {
        return $this->model->value;
    }

    public function setValue($value)
    {
        $this->model->value = $value;
        $this->model->save();
    }

    public function delete()
    {
        $this->model->delete();
    }

    /**
     * Возвращает массив AttributePossibleValue со значениями нужного аттрибута
     *
     * @param $attribute_id
     * @return array|bool
     */
    public static function ofAttribute($attribute_id)
    {
        $values = PossibleAttributeValue::ofAttribute($attribute_id)->get();

        if ($values) {
            $array = [];
            foreach ($values as $value) {
                $array[] = new self($value);
            }
            return $array;
        } else
            return false;
    }

    /**
     * @param integer $attr_id
     * @param string $value
     * @return AttributePossibleValue
     */
    public static function create($attr_id, $value)
    {
        $new_value = new PossibleAttributeValue();

        $new_value->value = $value;
        $new_value->attr_id = $attr_id;

        $new_value->save();

        return new self($new_value);

    }

    /**
     * @param $id
     * @return AttributePossibleValue|bool
     */
    public static function find($id)
    {
        $value = PossibleAttributeValue::find($id);

        if ($value)
            return new self($value);
        else
            return false;
    }

}