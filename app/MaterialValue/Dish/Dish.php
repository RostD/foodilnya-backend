<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 19.01.2017
 * Time: 23:31
 */

namespace App\MaterialValue\Dish;


use App\Http\Helpers\Notifications;
use App\MaterialValue\Material;
use App\Models\MaterialValue;

class Dish
{
    /**
     * @return array|bool (array of Material)
     */
    public static function getDishes()
    {
        $result = MaterialValue::dishes()->get();

        if (!$result)
            return false;

        Notifications::add('Ура! Блюда нашлись!');

        $dishes = [];
        foreach ($result as $value) {
            $dishes[] = new Material($value);
        }

        return $dishes;
    }
}