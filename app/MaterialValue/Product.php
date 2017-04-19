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
     * @var Material
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

            $ingredient = $this->model->parents()->first();
            if ($ingredient) {
                if ($ingredient->type_id == Ingredient::type_id)
                    $this->dishComponent = new Ingredient($ingredient);
                elseif ($ingredient->type_id == Adaptation::type_id)
                    $this->dishComponent = new Adaptation($ingredient);
            }

            $this->component_loaded = true;
        }
    }

    public function setDishComponent($id, $quantity = null)
    {
        if ($this->issetIngredient($id)) return;

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            $this->model->parents()->detach([$this->dishComponent->id]);
            $this->model->parents()->attach($ingredient->id);
            $this->component_loaded = false;
        }
    }

    public function issetIngredient($id)
    {
        // Сомнительный метод!
        $this->loadDishComponent();

        if (!$this->dishComponent) return false;

        if ($this->dishComponent->id == $id) return true;

        return false;
    }

    /**
     * Получить ингредиент, к которому относится данный товар
     * @return Material
     */
    public function getDishComponent()
    {
        $this->loadDishComponent();
        return $this->dishComponent;
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