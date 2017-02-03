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

    protected $table = "material_values";

    public function type()
    {
        return $this->belongsTo(TypeOfMaterialValue::class, "type_id");
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function parent()
    {
        return $this->belongsTo(MaterialValue::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MaterialValue::class, 'parent_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeOfMaterialValue::class, 'material_attribute', 'material_id', 'attribute_id')
            ->withPivot('value');
    }

    public function scopeDishes($query)
    {
        return $query->where('type_id', $this->dish_type_id);
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
}
