<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 02.05.2017
 * Time: 11:30
 */

namespace App\Report;


use App\MaterialValue\Adaptation;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\Models\MaterialValue;

class ReportMaterialString
{
    protected $model;
    protected $quantity;
    protected $material = false;

    /**
     * ReportMaterialString constructor.
     * @param MaterialValue $model
     * @param float $quantity
     */
    public function __construct(MaterialValue $model, float $quantity)
    {
        $this->model = $model;
        $this->quantity = $quantity;
    }

    public function __set($name, $value)
    {
        $name = "set" . $name;
        return $this->$name($value);
    }

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    private function loadMaterial()
    {
        if (!$this->material) {
            if ($this->model->type_id == Dish::type_id)
                $this->material = new Dish($this->model);
            elseif ($this->model->type_id == Adaptation::type_id)
                $this->material = new Adaptation($this->model);
            elseif ($this->model->type_id == Ingredient::type_id)
                $this->material = new Ingredient($this->model);;

        }
    }

    public function getMaterial()
    {
        $this->loadMaterial();
        return $this->material;
    }


}