<?php

namespace App\Models;

use App\MaterialValue\Adaptation;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\MaterialValue\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer type_id
 * @property mixed name
 * @property mixed unit_id
 * @property mixed unit
 * @property mixed parent
 * @property mixed description
 */
class MaterialValue extends Model
{
    use SoftDeletes;

    protected $table = "material_values";
    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function type()
    {
        return $this->belongsTo(TypeOfMaterialValue::class, "type_id");
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function parents()
    {
        return $this->belongsToMany(MaterialValue::class, 'material_material', 'material_child', 'material_parent');
    }

    public function children()
    {
        return $this->belongsToMany(MaterialValue::class, 'material_material', 'material_parent', 'material_child');
    }


    public function attributes()
    {
        return $this->belongsToMany(AttributeOfMaterialValue::class, 'material_attribute', 'material_id', 'attribute_id')
            ->withPivot('value');
    }

    public function scopeIngredientsOfDish()
    {
        return $this->children()->where('type_id', Ingredient::type_id)->withPivot('quantity', 'id');
    }

    public function scopeAdaptationsOfDish()
    {
        return $this->children()->where('type_id', Adaptation::type_id)->withPivot('quantity', 'id');
    }

    public function scopeProperties()
    {
        return $this->attributes()->where('name', 'not like', '#%');
    }

    public function scopeTags()
    {
        return $this->attributes()->where('name', 'like', '#%');
    }

    public function scopeDishes($query)
    {
        return $query->where('type_id', Dish::type_id);
    }

    public function scopeDish($query, $id)
    {
        return $query->where('type_id', Dish::type_id)->where('id', $id);
    }

    public function scopeProduct($query, $id)
    {
        return $query->where('type_id', Product::type_id)->where('id', $id);
    }

    public function scopeProducts($query)
    {
        return $query->where('type_id', Product::type_id);
    }

    public function scopeIngredient($query, $id)
    {
        return $query->where('type_id', Ingredient::type_id)->where('id', $id);
    }

    public function scopeIngredients($query)
    {
        return $query->where('type_id', Ingredient::type_id);
    }

    public function scopeAdaptation($query, $id)
    {
        return $query->where('type_id', Adaptation::type_id)->where('id', $id);
    }

    public function scopeAdaptations($query)
    {
        return $query->where('type_id', Adaptation::type_id);
    }
}
