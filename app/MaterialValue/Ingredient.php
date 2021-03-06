<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.02.2017
 * Time: 9:35
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

class Ingredient extends DishComponent
{
    const type_id = 1;
    /**
     * Товары, конкретизирующие данный ингредиент
     *
     * @var Material в массиве
     *
     */


    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (count($this->getDishes()) == 0 && count($this->getProducts()) == 0)
                $this->model->forceDelete();
            else
                $this->model->delete();
        }

    }


    public function toArray()
    {
        $array = parent::toArray();
        $array['availableUnits'] = $this->getAvailableUnits();
        return $array;
    }

    /**
     * @param int $id
     * @param bool $withTrashed
     * @return Ingredient|bool
     */
    public static function find($id, $withTrashed = true)
    {
        $ingredient = MaterialValue::ingredient($id);

        if ($withTrashed)
            $ingredient = $ingredient->withTrashed();

        $ingredient = $ingredient->first();

        if ($ingredient)
            return self::initial(self::class, $ingredient);
        return false;
    }

    public static function allNotUsed($dishId)
    {
        $dish = Dish::find($dishId);

        $modelsHaveParent = MaterialValue::ingredients()->whereHas('parents', function ($query) use ($dishId) {
            $query->where('material_parent', '<>', $dishId);
        })->get();

        $modelsDoesntHaveParent = MaterialValue::ingredients()->doesntHave('parents')->get();


        if ($modelsHaveParent || $modelsDoesntHaveParent) {
            $notUsedIngredients = [];
            foreach ($modelsHaveParent as $model) {
                if (!$dish->issetIngredient($model->id))
                    $notUsedIngredients[] = new self($model);
            }

            foreach ($modelsDoesntHaveParent as $model) {
                $notUsedIngredients[] = new self($model);
            }

            return $notUsedIngredients;
        }
        return false;
    }

    public static function all($withTrashed = true)
    {

        $models = MaterialValue::ingredients();

        if ($withTrashed)
            $models = $models->withTrashed();

        $models = $models->get();

        if ($models) {
            $ingredients = [];
            foreach ($models as $model) {
                $ingredients[] = new self($model);
            }
            return $ingredients;
        }
        return false;
    }

    public static function create(string $name, int $unit_id)
    {
        $model = self::createMaterial($name, Ingredient::type_id, $unit_id);

        if ($model) {
            return new self($model);
        }
        return false;
    }
}