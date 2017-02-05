<?php

namespace App\Http\Controllers;

use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\MaterialValue\Property;
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
        return view('welcome');
    }

    function showMaterial($materialId)
    {
        $material = Dish::find($materialId);
        return view("test.material", ['material' => $material]);
    }

    function ingredient($id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->productAdd(3);
        return view('test.ingredient', ['material' => $ingredient]);
    }

    function showAttribute($attr_id)
    {
        $attribute = Property::find($attr_id);
        return view("test.attribute", ['attribute' => $attribute]);
    }
}
