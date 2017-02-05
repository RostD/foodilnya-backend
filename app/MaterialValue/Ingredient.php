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

    public function productAdd($id)
    {
        if ($this->productBelongs($id))
            return;

        $product = Product::find($id);

        if ($product) {
            $this->model->children()->attach($product->id);
        }
    }

    public function productBelongs($id)
    {
        foreach ($this->products as $product) {
            if ($product->id == $id)
                return true;
        }

        return false;
    }

    public function getDishes()
    {
        return $this->dishes;
    }

    public function dishAdd($id)
    {

        if ($this->dishBelongs($id))
            return;


        $dish = Dish::find($id);
        if ($dish) {
            $this->model->parents()->attach($dish->id);
        }
    }

    public function dishBelongs($id)
    {
        foreach ($this->dishes as $dish) {
            if ($dish->id == $id)
                return true;
        }
        return false;
    }

    /**
     * @param int $id
     * @return bool|Ingredient
     */
    public static function find($id)
    {
        $model = MaterialValue::ingredient($id)->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }
}