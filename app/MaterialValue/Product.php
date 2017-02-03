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
    /**
     * Ингредиент, являющийся абстракцией для данного элемента
     * @var Material
     */
    protected $abstract;

    /**
     * Product constructor.
     * @param MaterialValue $model
     */
    public function __construct(MaterialValue $model)
    {
        parent::__construct($model);
        $this->loadAbstraction();
    }

    /**
     * Обновляет информацию об ингредиенте
     * @return void
     */
    private function loadAbstraction()
    {
        $this->abstract = false;

        if ($this->model->parent)
            $this->abstract = new Ingredient($this->model->parent);
    }

    /**
     * Получить ингредиент, к которому относится данный товар
     * @return Ingredient|bool
     */
    public function getAbstraction()
    {
        return $this->abstract;
    }

    public static function find($id)
    {
        $model = MaterialValue::product($id)->first();
        if ($model)
            return self::initial(self::class, $model);
        return false;
    }
}