<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 05.02.2017
 * Time: 18:12
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

/**
 * Class Adaptation
 * Приспособления для приготовления блюд
 * @package App\MaterialValue
 */
class Adaptation extends DishComponent
{
    const type_id = 4;

    /**
     * Измеряется только в штуках
     */
    const unit_id = 1;
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

    public static function create($name)
    {
        $model = self::createMaterial($name, Adaptation::type_id, Adaptation::unit_id);

        if ($model) {
            $adaptation = new self($model);
            return $adaptation;
        }
        return false;
    }
}