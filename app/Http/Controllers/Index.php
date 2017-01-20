<?php

namespace App\Http\Controllers;

use App\MaterialValue\Attribute;
use App\MaterialValue\Material;
use Illuminate\Http\Request;

class Index extends Controller
{
    public function index(Request $request)
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

        /*$materials = \App\Models\MaterialValue::all();

         foreach ($materials as $material) {
             echo "<b>Наименование</b>: " . $material->name . "<br />";
             echo "<b>Тип:</b> " . $material->type->name . "<br />";
             echo "<b>Атрибуты:</b><br />";
             foreach ($material->attributes as $attribute) {
                 echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $attribute->name . " = " . $attribute->pivot->value . "<br />";
             }
             echo "<br />";
         }*/


        //$material = Material::find(2);
        //$material->setAttribute(6, 'нет');
        print_r($request->headers);
        return view('backend.welcome');
    }

    function showMaterial($materialId)
    {
        $material = Material::find($materialId);
        return view("backend.test.material", ['material' => $material]);
    }

    function showAttribute($attr_id)
    {
        $attribute = Attribute::find($attr_id);
        return view("backend.test.attribute", ['attribute' => $attribute]);
    }
}
