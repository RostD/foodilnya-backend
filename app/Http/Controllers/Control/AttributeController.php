<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\MaterialValue\Property;
use App\MaterialValue\Unit;
use App\Models\TypeOfMaterialValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    public function attributes()
    {
        $data['properties'] = Property::all();
        return view('control.system.attribute.attributes', $data);
    }

    public function formEdit($id)
    {
        $data['property'] = Property::find($id);
        if (!$data['property'])
            abort(404);
        return view('control.system.attribute.formEdit', $data);
    }

    public function formAdd()
    {
        $data['units'] = Unit::all(false);
        $data['types'] = TypeOfMaterialValue::all();
        return view('control.system.attribute.formAdd', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);


        $property = Property::find($id);

        if ($property) {

            $possibleValues = [];

            if (!empty(trim($request->input('possibleValues'))))
                $possibleValues = explode(',', $request->input('possibleValues'));

            $property->setName(trim($request->input('name')));
            $property->replacePossibleValues($possibleValues);
            $property->setFixedValue((bool)$request->input('fixedValues'));
            DB::commit();

            return redirect()->action('Control\AttributeController@formEdit', ['id' => $property->id]);
        }
        abort(404);
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'unit' => 'exists:units,id'
        ]);

        $possibleValues = [];

        if (!empty(trim($request->input('possibleValues'))))
            $possibleValues = explode(',', trim($request->input('possibleValues')));


        $property = Property::create($request->input('name'),
            (bool)$request->input('fixedValues'),
            $request->input('type'),
            $request->input('unit'),
            $possibleValues);

        if (!$property)
            echo "Ошибка";

        return redirect()->action('Control\AttributeController@formAdd');

    }

    public function destroy($id)
    {
        $property = Property::find($id);
        if ($property) {
            $property->destroy();
        }
    }
}
