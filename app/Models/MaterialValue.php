<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer type_id
 * @property mixed name
 * @property mixed unit_id
 * @property mixed unit
 * @property mixed parent
 */
class MaterialValue extends Model
{
    private $dish_type_id = 3;
    private $product_type_id = 2;
    private $ingredient_type_id = 1;
    private $adaptation_type_id = 4;

    protected $table = "material_values";

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
        return $this->children()->where('type_id', $this->ingredient_type_id);
    }

    public function scopeAdaptationsOfDish()
    {
        return $this->children()->where('type_id', $this->adaptation_type_id);
    }

    public function scopeProperties()
    {
        return $this->attributes()->where('name', 'not like', '#%')->get();
    }

    public function scopeTags()
    {
        return $this->attributes()->where('name', 'like', '#%')->get();
    }

    public function scopeDishes($query)
    {
        return $query->where('type_id', $this->dish_type_id)->get();
    }

    public function scopeDish($query, $id)
    {
        return $query->where('type_id', $this->dish_type_id)->where('id', $id);
    }

    public function scopeProduct($query, $id)
    {
        return $query->where('type_id', $this->product_type_id)->where('id', $id);
    }

    public function scopeIngredient($query, $id)
    {
        return $query->where('type_id', $this->ingredient_type_id)->where('id', $id);
    }

    public function scopeAdaptation($query, $id)
    {
        return $query->where('type_id', $this->adaptation_type_id)->where('id', $id);
    }
}
