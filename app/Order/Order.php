<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 23.04.2017
 * Time: 8:37
 */

namespace App\Order;


use App\Models\OrderModel;

class Order
{
    protected $model;
    protected $client = false;

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


}