<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 23.04.2017
 * Time: 8:37
 */

namespace App\Order;


use App\MaterialValue\Dish;
use App\Models\OrderModel;

class Order
{
    protected $model;
    protected $client = false;

    protected $material_values;
    protected $material_values_loaded = false;


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
     * @return mixed
     */
    public function getId()
    {
        return $this->model->id;
    }

    /**
     * @return mixed
     */
    public function getConfirmed()
    {
        return $this->model->confirmed;
    }

    /**
     * @param mixed $confirmed
     */
    public function setConfirmed(bool $confirmed)
    {
        $this->model->confirmed = $confirmed;
        $this->model->save();
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->model->address;
    }

    /**
     * @return mixed
     */
    public function getDone()
    {
        return $this->model->done;
    }

    /**
     * @param mixed $done
     */
    public function setDone(bool $done)
    {
        $this->model->done = $done;
        $this->model->save();
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->model->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->model->date = $date;
        $this->model->save();
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->model->address = trim($address);
        $this->model->save();
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        if (!$this->client)
            $this->client = Client::find($this->model->user_id);

        return $this->client;
    }

    /**
     * @param int $client_id
     */
    public function setClient(int $client_id)
    {
        $client = Client::find($client_id);

        if ($client) {
            $this->model->user_id = $client->id;
            $this->model->save();
        }

    }

    public function __construct(OrderModel $model)
    {
        $this->model = $model;
    }

    private function loadMaterialValues()
    {
        //TODO
        if (!$this->material_values_loaded) {
            $this->material_values = [];

            foreach ($this->model->materialStrings as $materialString) {
                if ($materialString->materialValue->type_id == Dish::type_id) {
                }

            }
        }
    }

    public function getMaterialValues()
    {
        $this->loadMaterialValues();
        return $this->material_values;
    }

    public static function all($withTrashed = true)
    {
        $models = OrderModel::orderBy('date');

        if ($withTrashed)
            $models = $models->withTrashed();

        $models = $models->get();

        if ($models) {
            $orders = [];
            foreach ($models as $model)
                $orders[] = new self($model);

            return $orders;
        }
        return false;
    }

    public function trashed()
    {
        return $this->model->trashed();
    }


}