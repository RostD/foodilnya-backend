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
    protected $ingredient = null;
    protected $ingr_loaded = false;

    /**
     * Обновляет информацию об ингредиенте
     * @return void
     */
    private function loadIngredient()
    {
        if (!$this->ingr_loaded) {
            $this->ingredient = null;

            $ingredient = $this->model->parents()->first();
            if ($ingredient)
                $this->ingredient = new Ingredient($ingredient);

            $this->ingr_loaded = true;
        }
    }

    public function setIngredient($id)
    {
        if ($this->issetIngredient($id)) return;

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            $this->model->parents()->detach([$this->ingredient->id]);
            $this->model->parents()->attach($ingredient->id);
            $this->ingr_loaded = false;
        }
    }

    public function issetIngredient($id)
    {
        $this->loadIngredient();

        if (!$this->ingredient) return false;

        if ($this->ingredient->id == $id) return true;

        return false;
    }

    /**
     * Получить ингредиент, к которому относится данный товар
     * @return Ingredient|bool
     */
    public function getIngredient()
    {
        $this->loadIngredient();
        return $this->ingredient;
    }

    /**
     * @param int $id
     * @return bool|Product
     */
    public static function find($id)
    {
        $model = MaterialValue::product($id)->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }
}