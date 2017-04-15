<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.02.2017
 * Time: 9:35
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

class Ingredient extends DishComponent
{
    const type_id = 1;
    /**
     * Товары, конкретизирующие данный ингредиент
     *
     * @var Material в массиве
     *
     */
    protected $products = [];
    protected $prod_loaded = false;

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

    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (count($this->getDishes()) == 0 && count($this->getProducts()) == 0)
                $this->model->forceDelete();
            else
                $this->model->delete();
        }

    }

    /**
     * @param int $id
     * @return bool|Ingredient
     */
    public static function find($id)
    {
        $ingredient = MaterialValue::ingredient($id)->withTrashed()->first();
        if ($ingredient)
            return self::initial(self::class, $ingredient);
        return false;
    }

    public static function all()
    {
        $models = MaterialValue::ingredients()->withTrashed()->get();

        if ($models) {
            $ingredients = [];
            foreach ($models as $model) {
                $ingredients[] = new self($model);
            }
            return $ingredients;
        }
        return false;
    }

    public static function create(string $name, int $unit_id)
    {
        $model = self::createMaterial($name, Ingredient::type_id, $unit_id);

        if ($model) {
            return new self($model);
        }
        return false;
    }
}