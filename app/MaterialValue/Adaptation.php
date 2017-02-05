<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 05.02.2017
 * Time: 18:12
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

class Adaptation extends Material
{
    protected $dishes = [];

    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
        $this->loadDishes();
    }

    private function loadDishes()
    {
        $this->dishes = [];

        foreach ($this->model->parents as $dish) {
            $this->dishes[] = new Dish($dish);
        }
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
            $this->loadDishes();
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
     * Ищет материал по его id и возвращает его модель
     * @param integer $id
     * @return MaterialValue|bool
     */
    public static function find($id)
    {
        $adaptation = MaterialValue::adaptation();

        if ($adaptation)
            return self::initial(self::class, $adaptation);

        return false;
    }
}