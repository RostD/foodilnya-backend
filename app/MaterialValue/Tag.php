<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.02.2017
 * Time: 13:48
 */

namespace App\MaterialValue;


use App\Models\AttributeOfMaterialValue;

/**
 * Class Tag
 * Тег не подразделяется на типы материальных ценностей.
 * Т.е. один и тот же тег может быть использован как блюдом, так товаром или ингредиентом
 * @package App\MaterialValue
 */
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

    /**
     * Возвращает все теги, которые были использованны блюдами
     *
     * @return bool
     */
    public static function allUsedDishesTags()
    {
        return self::allUsedTags(Dish::type_id);
    }

    /**
     * Возвращает все теги, которые были использованны приспособлениями
     *
     * @return bool
     */
    public static function allUsedAdaptationsTags()
    {
        return self::allUsedTags(Adaptation::type_id);
    }

    public static function allUsedProductsTags()
    {
        return self::allUsedTags(Product::type_id);
    }

    public static function allUsedIngredientsTags()
    {
        return self::allUsedTags(Ingredient::type_id);
    }

    private static function allUsedTags(int $type_id)
    {
        $tags = AttributeOfMaterialValue::usedTags($type_id)->get();
        if ($tags) {
            return $tags;
        }
        return false;
    }

    public static function all($withTrashed = true)
    {

        $tags = AttributeOfMaterialValue::tags();
        if ($withTrashed)
            $tags = $tags->withTrashed();

        $tags = $tags->get();

        if ($tags) {
            $objs = [];
            foreach ($tags as $tag) {
                $objs[] = new self($tag);
            }
            return $objs;
        }
        return false;
    }

    public static function create(string $name)
    {
        if (trim($name)) {

            if (substr($name, 0, 1) != "#")
                $name = "#" . mb_strtolower($name);

            $res = AttributeOfMaterialValue::where('name', $name)->first();
            if ($res)
                return new self($res);

            $tag = new AttributeOfMaterialValue();
            $tag->name = $name;
            $tag->save();

            return new self($tag);
        }
        return false;
    }
}