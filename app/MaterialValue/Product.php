<?php
/**
 * Created by PhpStorm.
 * User: Ростислав
 * Date: 03.02.2017
 * Time: 9:41
 */

namespace App\MaterialValue;


use App\Models\MaterialValue;

class Product extends Material
{
    const type_id = 2;
    /**
     * Ингредиент, являющийся абстракцией для данного элемента
     * @var IngredientCounted|AdaptationCounted
     */
    protected $dishComponent = null;
    protected $component_loaded = false;

    /**
     * Обновляет информацию об ингредиенте
     * @return void
     */
    private function loadDishComponent()
    {
        if (!$this->component_loaded) {
            $this->dishComponent = null;

            $component = $this->model->ProductsDishComponent()->first();

            if ($component) {
                if ($component->type_id == Ingredient::type_id) {
                    $this->dishComponent = new IngredientCounted($component);
                    $this->dishComponent->quantity = $component->pivot->quantity;
                } elseif ($component->type_id == Adaptation::type_id) {
                    $this->dishComponent = new AdaptationCounted($component);
                    $this->dishComponent->quantity = $component->pivot->quantity;
                }
            }

            $this->component_loaded = true;
        }
    }

    public function setDishComponent($id, $quantity, int $quantityUnit)
    {
        $component = Ingredient::find($id, false);
        if (!$component)
            $component = Adaptation::find($id, false);

        if ($component) {
            $quantity = Unit::convert($quantity, $quantityUnit, $component->unit);

            if ($this->issetComponent($component->id)) {
                $this->model->parents()->updateExistingPivot($component->id, ['quantity' => $quantity]);
            } else {
                $this->model->parents()->attach($component->id, ['quantity' => $quantity]);
                $this->component_loaded = false;
            }
        }
    }

    public function issetComponent($id = null)
    {
        $this->loadDishComponent();

        if (!$this->dishComponent) return false;

        if ($this->dishComponent->id == $id) return true;

        return false;
    }

    /**
     * Получить ингредиент, к которому относится данный товар
     * @return AdaptationCounted|IngredientCounted
     */
    public function getDishComponent()
    {
        $this->loadDishComponent();
        return $this->dishComponent;
    }

    public function destroy()
    {
        if ($this->trashed()) {
            $this->model->restore();
        } else {
            if (!$this->getDishComponent())// TODO && Нет поставщиков
                $this->model->forceDelete();
            else
                $this->model->delete();
        }

    }

    public function removeComponent($id)
    {
        if ($this->issetComponent($id)) {
            $this->model->parents()->detach($id);
        }
    }

    /**
     * @param int $id
     * @return bool|Product
     */
    public static function find($id)
    {
        $model = MaterialValue::product($id)->withTrashed()->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }

    public static function create(string $name, int $unit_id)
    {
        $model = self::createMaterial($name, Product::type_id, $unit_id);

        if ($model) {
            return new self($model);
        }
        return false;
    }
}