<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 06.02.2017
 * Time: 8:26
 */

namespace App\MaterialValue;


abstract class DishComponent extends Material
{
    protected $dishes = [];
    protected $dishes_loaded = false;


    protected function loadDishes()
    {
        if (!$this->dishes_loaded) {
            $this->dishes = [];

            foreach ($this->model->parents as $dish) {
                $this->dishes[] = new Dish($dish);
            }
            $this->dishes_loaded = true;
        }

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
}