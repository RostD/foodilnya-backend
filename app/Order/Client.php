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

    public function getAddress()
    {
        return $this->model->address;
    }

    public function getPhone()
    {
        return $this->model->phone;
    }

    public function trashed()
    {
        return $this->model->trashed();
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
}