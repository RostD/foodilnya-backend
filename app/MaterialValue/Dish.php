<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 19.01.2017
 * Time: 23:31
 */

namespace App\MaterialValue;


use App\Http\Helpers\Notifications;
use App\Models\MaterialValue;

class Dish extends Material
{

    /**
     * Рейтинг блюда среди клиентов
     * @var int
     */
    protected $rate;

    /**
     * Dish constructor.
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
        $this->rate = random_int(0, 5);
    }    

    /**
     * @return array|bool (Dish[])
     */
    public static function all()
    {
        $result = MaterialValue::dishes()->get();

        if (!$result)
            return false;

        Notifications::add('Ура! Блюда нашлись!');

        $dishes = [];
        foreach ($result as $value) {
            $dishes[] = new self($value);
        }
        return $dishes;
    }

    /**
     * Ищет материал по его id и возвращает его модель
     * @param integer $id
     * @return MaterialValue|bool
     */
    public static function find($id)
    {
        $model = MaterialValue::dish($id)->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }

}