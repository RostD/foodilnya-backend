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
    protected $prod_loaded = false;

    protected $dishes = [];
    protected $dishes_loaded = false;

    /**
     * Ingredient constructor.
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
    }

    /**
     * Загружает конкретизирующие товары
     * @return void
     */
    private function loadProducts()
    {
        if (!$this->prod_loaded) {
            $this->products = [];

            foreach ($this->model->children as $products) {
                $this->products[] = new Product($products);
            }
            $this->prod_loaded = true;
        }
    }

    private function loadDishes()
    {
        if (!$this->dishes_loaded) {
            $this->dishes = [];

            foreach ($this->model->parents as $dish) {
                $this->dishes[] = new Dish($dish);
            }
            $this->dishes_loaded = true;
        }
    }

    /**
     * Получить товары, конкретизирующий этот ингредиент
     * @return Material в массиве
     */
    public function getProducts()
    {
        $this->loadProducts();
        return $this->products;
    }

    public function addProduct($id)
    {
        if ($this->issetProduct($id))
            return;

        $product = Product::find($id);

        if ($product) {
            $this->model->children()->attach($product->id);
            $this->prod_loaded = false;
        }
    }

    public function issetProduct($id)
    {
        $this->loadProducts();
        foreach ($this->products as $product) {
            if ($product->id == $id)
                return true;
        }

        return false;
    }

    public function getDishes()
    {
        $this->loadDishes();
        return $this->dishes;
    }

    public function addDish($id)
    {

        if ($this->issetDish($id))
            return;


        $dish = Dish::find($id);
        if ($dish) {
            $this->model->parents()->attach($dish->id);
            $this->dishes_loaded = false;
        }
    }

    public function issetDish($id)
    {
        $this->loadDishes();
        
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
        $ingredient = MaterialValue::ingredient($id)->first();
        if ($ingredient)
            return self::initial(self::class, $ingredient);
        return false;
    }
}