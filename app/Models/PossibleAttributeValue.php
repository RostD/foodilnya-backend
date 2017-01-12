<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PossibleAttributeValue extends Model
{
    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo(AttributeOfMaterialValue::class, 'attr_id');
    }

    public function scopeOfAttribute($query, $id)
    {
        return $query->where('attr_id', $id);
    }
}
