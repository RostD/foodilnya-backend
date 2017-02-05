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
    protected $dishes_loaded = false;

    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
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
     * Ищет материал по его id и возвращает его модель
     * @param integer $id
     * @return Adaptation|bool
     */
    public static function find($id)
    {
        $adaptation = MaterialValue::adaptation($id)->first();

        if ($adaptation)
            return self::initial(self::class, $adaptation);

        return false;
    }
}