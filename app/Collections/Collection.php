<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 15:35
 */

namespace App\Collections;


class Collection implements ICollections
{
    protected $typeItem;
    protected $data = [];

    public function __construct($type_items)
    {
        if (class_exists($type_items) || interface_exists($type_items)) {
            $this->typeItem = $type_items;
        } else {
            // TODO: Exception ("неизвестный тип")
            exit("Неизвестный тип для типизации коллеции");
        }

    }

    public function add($item)
    {
        if ($this->checkType($item)) {
            $this->data[] = $item;
        } else {
            // TODO: Exception ("неподходищий тип")
            exit("Не подходит тип объекта для Коллекции");
        }
    }

    public function remove($item)
    {
        if ($this->checkType($item)) {
            foreach ($this->data as $key => $value) {
                if ($item == $value)
                    unset($this->data[$key]);
            }
        }
    }

    public function array()
    {
        return $this->data;
    }

    protected function checkType($item)
    {
        if ($item instanceof $this->typeItem) {
            return true;
        } else {
            return false;
        }
    }

    public function getType()
    {
        return $this->typeItem;
    }
}