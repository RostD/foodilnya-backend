<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.02.2017
 * Time: 9:35
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

class Ingredient extends Material
{
    /**
     * Товары, конкретизирующие данный ингредиент
     *
     * @var Material в массиве
     *
     */
    protected $specifics;

    /**
     * Ingredient constructor.
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
        $this->loadSpecifics();
    }

    /**
     * Загружает конкретизирующие товары
     * @return void
     */
    private function loadSpecifics()
    {
        $this->specifics = [];

        foreach ($this->model->children as $child) {
            $this->specifics[] = new Product($child);
        }
    }

    /**
     * Получить товары, конкретизирующий этот ингредиент
     * @return Material в массиве
     */
    public function getSpecifics()
    {
        return $this->specifics;
    }

    public static function find($id)
    {
        $model = MaterialValue::ingredient($id)->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }
}