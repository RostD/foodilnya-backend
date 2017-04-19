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

    protected $props = [];
    protected $props_loaded = false;

    /**
     * Тип материальной ценности - блюдо
     */
    const type_id = 3;

    /**
     * Единица измерения - порция
     */
    const unit_id = 5;

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
                $ingredient = new IngredientCounted($i);
                $ingredient->quantity = $i->pivot->quantity;
                $this->ingredients[] = $ingredient;
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

    public function getIngredient($id)
    {
        $this->loadIngredients();
        foreach ($this->ingredients as $i) {
            if ($i->id == $id) return $i;
        }
        return false;
    }

    /**
     * Проверяет, есть ли в составе бюда переданный ингредиент
     *
     * @param $id
     * @return bool
     */
    public function issetIngredient($id)
    {
        $this->loadIngredients();
        foreach ($this->ingredients as $i) {
            if ($i->id == $id) return true;
        }
        return false;
    }

    /**
     * @param $id
     * @param $quantity
     * @param integer $quantityUnit
     */
    public function addIngredient($id, $quantity, int $quantityUnit)
    {
        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            $quantity = Unit::convert($quantity, $quantityUnit, $ingredient->unit);

            if ($this->issetIngredient($ingredient->id)) {
                $this->model->children()->updateExistingPivot($ingredient->id, ['quantity' => $quantity]);
            } else {
                $this->model->children()->attach($ingredient->id, ['quantity' => $quantity]);
                $this->ing_loaded = false;
            }
        }

    }

    public function removeIngredient($id)
    {
        if ($this->issetIngredient($id)) {
            $this->model->children()->detach($id);
        }
    }

    private function loadAdaptations()
    {
        if (!$this->adapt_loaded) {
            $this->adaptations = [];

            foreach ($this->model->adaptationsOfDish()->get() as $a) {

                $adaptation = new AdaptationCounted($a);
                $adaptation->quantity = $a->pivot->quantity;
                $this->adaptations[] = $adaptation;
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

    public function getAdaptation($id)
    {
        $this->loadAdaptations();
        foreach ($this->adaptations as $a) {
            if ($a->id == $id) return $a;
        }
        return false;
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

    public function addAdaptation($id, $quantity)
    {
        $adaptation = Adaptation::find($id);

        if ($adaptation) {

            if ($this->issetAdaptation($adaptation->id)) {
                $this->model->children()->updateExistingPivot($adaptation->id, ['quantity' => $quantity]);
            } else {
                $this->model->children()->attach($adaptation->id, ['quantity' => $quantity]);
                $this->ing_loaded = false;
            }
        }
    }

    public function removeAdaptation($id)
    {
        if ($this->issetAdaptation($id)) {
            $this->model->children()->detach($id);
        }
    }

    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (count($this->getAdaptations()) == 0 && count($this->getIngredients()) == 0)
                $this->model->forceDelete();
            else
                $this->model->delete();
        }

    }
    
    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    public function toArray()
    {
        $array = parent::toArray();
        $array['rate'] = $this->rate;
        return $array;
    }
    
    /**
     * Все существующие блюда,
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
     * @return Dish|bool
     */
    public static function find($id)
    {
        $model = MaterialValue::dish($id)->withTrashed()->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }

    public static function create($name)
    {
        $model = self::createMaterial($name, Dish::type_id, Dish::unit_id);

        if ($model) {
            $dish = new self($model);
            return $dish;
        }
        return false;
    }

}