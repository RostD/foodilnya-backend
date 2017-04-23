<?php
/**
 * Created by PhpStorm.
 * User: лол
 * Date: 22.04.2017
 * Time: 4:33
 */

namespace App\Order;


use App\User;

class Client
{
    protected $model;
    const role_id = 3;

    protected $orders;
    protected $orders_loaded = false;

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->model->address = trim($address);
        $this->model->save();
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->model->phone = trim($phone);
        $this->model->save();
    }

    public function getLogin()
    {
        return $this->model->email;
    }

    public function setLogin($login)
    {
        $this->model->email = $login;
        $this->model->save();
    }

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function __get($name)
    {
        $name = "get" . $name;
        return $this->$name();
    }

    public function __set($name, $value)
    {
        $name = "set" . $name;
        return $this->$name($value);
    }

    public function getId()
    {
        return $this->model->id;
    }

    public function getName()
    {
        return $this->model->name;
    }

    public function setName($name)
    {
        $this->model->name = $name;
        $this->model->save();
    }

    public function getAddress()
    {
        return $this->model->address;
    }

    public function getPhone()
    {
        return $this->model->phone;
    }

    private function loadOrders()
    {
        if (!$this->orders_loaded) {
            $this->orders = [];

            foreach ($this->model->orders as $model)
                $this->orders[] = new Order($model);

            $this->orders_loaded = true;
        }
    }

    public function getOrders()
    {
        $this->loadOrders();
        return $this->orders;
    }

    public function trashed()
    {
        return $this->model->trashed();
    }

    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (count($this->getOrders()) == 0)
                $this->model->forceDelete();
            else
                $this->model->delete();
        }
    }

    public static function all($withTrashed = true)
    {
        $models = User::clients();

        if ($withTrashed)
            $models = $models->withTrashed();

        $models = $models->get();

        $clients = [];
        foreach ($models as $model) {
            $clients[] = new self($model);
        }

        return $clients;
    }

    public static function create($name, $login, $password)
    {
        $model = User::create([
            'name' => $name,
            'email' => $login,
            'password' => bcrypt($password),
            'role_id' => Client::role_id,
        ]);

        if ($model)
            return new self($model);
        return false;
    }

    public static function find($id)
    {
        $model = User::client($id)->withTrashed()->first();

        if ($model)
            return new self($model);
        return false;
    }

}