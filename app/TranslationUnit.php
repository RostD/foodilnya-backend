<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslationUnit extends Model
{
    protected $table = "translation_units";
    public $timestamps = false;

    public function mainUnit()
    {
        return $this->belongsTo(Unit::class, 'main_unit', 'id');
    }

    public function transUnit()
    {
        return $this->belongsTo(Unit::class, 'trans_unit', 'id');
    }
}
