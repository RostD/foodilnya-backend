<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = "units";
    public $timestamps = false;
    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function attributes()
    {
        return $this->hasMany(AttributeOfMaterialValue::class, 'unit_id');
    }

    public function materialValues()
    {
        return $this->hasMany(MaterialValue::class, 'unit_id');
    }

    public function similarUnits()
    {
        return $this->belongsToMany(Unit::class, 'translation_units', 'main_unit', 'trans_unit')->withPivot('value');
    }

    public function mainUnits()
    {
        return $this->belongsToMany(Unit::class, 'translation_units', 'trans_unit', 'main_unit')->withPivot('value');
    }
}
