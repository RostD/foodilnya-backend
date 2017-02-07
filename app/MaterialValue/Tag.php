<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.02.2017
 * Time: 13:48
 */

namespace App\MaterialValue;


use App\Models\AttributeOfMaterialValue;

class Tag
{
    protected $model;
    protected $id;
    protected $name;

    public function __construct(AttributeOfMaterialValue $model)
    {
        $this->model = $model;

        $this->id = $model->id;
        $this->name = $model->name;
    }

    public function __get($name)
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

    public function toArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        return $array;

    }

    /**
     * @param $id
     * @return Tag|bool
     */
    public static function find($id)
    {
        $tag = AttributeOfMaterialValue::tag($id)->first();
        if ($tag)
            return new self($tag);
        return false;
    }
}