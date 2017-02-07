<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AttributeOfMaterialValue extends Model
{
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo||boolean
     */
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

    public function scopeProperty($query, $id)
    {
        return $query->where('id', $id)->where('name', 'not like', '#%');
    }

    public function scopeTag($query, $id)
    {
        return $query->where('id', $id)->where('name', 'like', '#%');
    }

    public static function usedTags(int $material_type_id = null)
    {
        $query = DB::table('material_attribute')
            ->select('material_attribute.attribute_id as id', 'attribute_of_material_values.name as name')
            ->join('material_values', 'material_attribute.material_id', 'material_values.id')
            ->join('attribute_of_material_values', 'material_attribute.attribute_id', 'attribute_of_material_values.id');

        if ($material_type_id)
            $query->where('material_values.type_id', $material_type_id);

        $query->where('attribute_of_material_values.name', 'LIKE', '#%')
            ->groupBy('id', 'name');

        return $query;
    }
}
