<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeOfMaterialValue extends Model
{
    public $timestamps = false;

    public function materialType()
    {
        return $this->belongsTo(TypeOfMaterialValue::class, 'material_type_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function materials()
    {
        return $this->belongsToMany(MaterialValue::class, 'material_attribute')
            ->withPivot('value');
    }

    public function possibleValues()
    {
        return $this->hasMany(PossibleAttributeValue::class, 'attr_id');
    }

}
