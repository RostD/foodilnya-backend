<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeOfMaterialValue extends Model
{
    public $timestamps = false;

    public function materialType()
    {
        return $this->belongsTo(TypeOfMaterialValue::class, 'material_type_id');
    }

    public function valueType()
    {
        return $this->belongsTo(ValueType::class, 'value_type_id');
    }

    public function materials()
    {
        return $this->belongsToMany(MaterialValue::class, 'material_attribute')
            ->withPivot('value');
    }

}
