<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 27.04.2017
 * Time: 13:03
 */

namespace App\Order;


use App\Interfaces\IRegisterString;
use App\MaterialValue\Adaptation;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\Models\MaterialValue;
use App\Models\OrderModel;
use App\Models\OrdersMaterialValueModel;

class OrderMaterialString extends OrderString implements IRegisterString
{
    protected $material;
    protected $material_loaded = false;

    protected $model;

    public function __construct(OrdersMaterialValueModel $model)
    {
        $this->model = $model;
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


    private function loadMaterial()
    {
        if (!$this->material) {
            if ($this->model->materialValue->type_id == Dish::type_id)
                $this->material = Dish::find($this->model->material_value_id);
            elseif ($this->model->materialValue->type_id == Adaptation::type_id)
                $this->material = Adaptation::find($this->model->material_value_id);
            elseif ($this->model->materialValue->type_id == Ingredient::type_id)
                $this->material = Ingredient::find($this->model->material_value_id);

            $this->material_loaded = true;
        }
    }

    public function getMaterial()
    {
        $this->loadMaterial();
        return $this->material;
    }

    public function getQuantity()
    {
        return $this->model->quantity;
    }

    public function setQuantity($value)
    {
        $this->model->quantity = $value;
        $this->model->save();
    }

    public function destroy()
    {
        $this->model->delete();
    }

    public static function create($material_id, $quantity, $order_id)
    {
        $material = MaterialValue::find($material_id);
        $order = OrderModel::find($order_id);

        if ($material && $order) {
            $materialString = new OrdersMaterialValueModel();
            $materialString->quantity = $quantity;
            $materialString->material_value_id = $material->id;
            $order->materialStrings()->save($materialString);
        }
    }

    public static function all($order_id)
    {
        $order = OrderModel::find($order_id);

        $strings = [];
        foreach ($order->materialStrings as $model) {
            $strings[] = new self($model);
        }

        return $strings;
    }

    public static function find($orderId, $materialId)
    {
        $model = OrdersMaterialValueModel::where([['order_id', $orderId], ['material_value_id', $materialId]])->first();

        if ($model)
            return new self($model);

        return false;
    }

    public function getMaterialId()
    {
        return $this->material->id;
    }

    public function getUnit()
    {
        return $this->material->unit;
    }
}