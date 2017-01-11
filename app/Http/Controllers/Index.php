<?php

namespace App\Http\Controllers;

use App\MaterialValue\MaterialValue;
use App\MaterialValue\MaterialValueAttribute;
use App\PropertyAttributes;
use App\ValueType;

class Index extends Controller
{
    public function index()
    {
        /* $f = new CountedMaterialValue(2, 'Мат. ценность3', 1, 20);
        $f2 = new CountedMaterialValue(3, 'Мат. ценность4', 1, 10);
        $f3 = new CountedMaterialValue(4, 'Мат. ценность5', 1, 5);
        
        $collection = new WarehouseCollection();
        $collection->add($f);
        $collection->add($f2);
        $collection->add($f3);

        $warehouse = new WarehouseBase(1);
        $warehouse->justPut($collection); */

        /* $base_unit = 2.3; // килограммы (id 2) выразить в граммах (id 3)
        $translator = TranslationUnit::where('main_unit',2)
                                        ->where('trans_unit',3)
                                        ->first();
        echo "$base_unit ".$translator->mainUnit->name." = ".$base_unit*$translator->value." ".$translator->transUnit->name;*/

        /*$materials = \App\MaterialValue::all();

         foreach ($materials as $material) {
             echo "<b>Наименование</b>: " . $material->name . "<br />";
             echo "<b>Тип:</b> " . $material->type->name . "<br />";
             echo "<b>Аттрибуты:</b><br />";
             foreach ($material->attributes as $attribute) {
                 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $attribute->name . " = " . $attribute->pivot->value . "<br />";
             }
             echo "<br />";
         }*/

        //$material = new MaterialValue(3);
        //echo "<b>".$material->id."</b>: ".$material->name." ".$material->typeName." ".$material->BaseUnitName;

        //$material = MaterialValue::find(3);
        //$material->setAttribute(4,90);
    }

    function showMaterial($materialId)
    {
        $material = MaterialValue::find($materialId);
        return view("test.material", ['material' => $material]);
    }

    function showAttribute($attr_id)
    {
        $attribute = MaterialValueAttribute::find($attr_id);
        return view("test.attribute", ['attribute' => $attribute]);
    }
}
