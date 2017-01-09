<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialValue extends Model
{

    protected $table = "material_values";

    public function type()
    {
        return $this->belongsTo(TypeOfMaterialValue::class, "type_id");
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeOfMaterialValue::class, 'material_attribute', 'material_id', 'attribute_id')
            ->withPivot('value');
    }
}
