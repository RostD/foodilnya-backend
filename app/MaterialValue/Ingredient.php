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
    protected $products = [];

    protected $dishes = [];

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    /**
     * Ingredient constructor.
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
        $this->loadProducts();
        $this->loadDishes();
    }

    /**
     * Загружает конкретизирующие товары
     * @return void
     */
    private function loadProducts()
    {
        $this->products = [];

        foreach ($this->model->children as $products) {
            $this->products[] = new Product($products);
        }
    }

    private function loadDishes()
    {
        $this->dishes = [];

        foreach ($this->model->parents as $dish) {
            $this->dishes[] = new Dish($dish);
        }
    }

    /**
     * Получить товары, конкретизирующий этот ингредиент
     * @return Material в массиве
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function getDishes()
    {
        return $this->dishes;
    }

    /**
     * @param $id
     */
    public function addDish($id)
    {

        if ($this->belongsDish($id))
            return;


        $dish = Dish::find($id);
        if ($dish) {
            $this->model->parents()->attach($dish->id);
        }
    }

    public function belongsDish($id)
    {
        foreach ($this->dishes as $dish) {
            if ($dish->id == $id)
                return true;
        }
        return false;
    }

    public static function find($id)
    {
        $model = MaterialValue::ingredient($id)->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }
}