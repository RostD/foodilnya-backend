<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 30.12.2016
 * Time: 14:10
 */

namespace App\Collections;


/**
 * Class Collection
 * @package App\Collections
 */
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

    /**
     * Добавить элемент в коллекцию
     * @param object $item Объект типа, указанного при инициализации коллекции
     */
    public function add($item)
    {
        if ($this->checkType($item)) {
            //TODO: проверить, есть ли уже такой объект в коллекции
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