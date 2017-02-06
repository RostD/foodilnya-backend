<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 05.02.2017
 * Time: 18:12
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

class Adaptation extends DishComponent
{
    
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