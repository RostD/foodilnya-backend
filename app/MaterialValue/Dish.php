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
    protected $ingredients = [];
    protected $ing_loaded = false;

    protected $adaptations = [];
    protected $adapt_loaded = false;

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

    private function loadIngredients()
    {
        if (!$this->ing_loaded) {
            $this->ingredients = [];

            foreach ($this->model->ingredientsOfDish()->get() as $i) {
                $this->ingredients[] = new Ingredient($i);
            }

            $this->ing_loaded = true;
        }
    }

    /**
     * @return array
     */
    public function getIngredients()
    {
        $this->loadIngredients();
        return $this->ingredients;
    }

    public function issetIngredient($id)
    {
        $this->loadIngredients();
        foreach ($this->ingredients as $i) {
            if ($i->id == $id) return true;
        }
        return false;
    }

    public function addIngredient($id)
    {
        if ($this->issetIngredient($id))
            return;

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            $this->model->children()->attach($ingredient->id);
            $this->ing_loaded = false;
        }
    }


    private function loadAdaptations()
    {
        if (!$this->adapt_loaded) {
            $this->adaptations = [];

            foreach ($this->model->adaptationsOfDish()->get() as $a) {
                $this->adaptations[] = new Adaptation($a);
            }
            $this->adapt_loaded = true;
        }

    }

    /**
     * @return array
     */
    public function getAdaptations()
    {
        $this->loadAdaptations();
        return $this->adaptations;
    }

    public function issetAdaptation($id)
    {
        $this->loadAdaptations();
        foreach ($this->adaptations as $a) {
            if ($a->id == $id)
                return true;
        }

        return false;
    }

    public function addAdaptation($id)
    {
        if ($this->issetAdaptation($id))
            return;
        $adaptation = Adaptation::find($id);
        if ($adaptation) {
            $this->model->children()->attach($adaptation->id);
            $this->adapt_loaded = false;
        }
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }
    
    /**
     * Все существующие блюда
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
     * Ищет блюдо по его id и возвращает его модель
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

    public function toArray()
    {
        $array = parent::toArray();
        $array['rate'] = $this->rate;
        return $array;
    }

}