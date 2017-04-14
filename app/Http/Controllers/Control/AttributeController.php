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

            $property->setName(trim($request->input('name')));
            $property->replacePossibleValues(explode(',', $request->input('possibleValues')));
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
            'unit' => 'required|exists:units,id'
        ]);

        Property::create($request->input('name'),
            (bool)$request->input('fixedValue'),
            $request->input('type'),
            $request->input('unit'),
            explode(',', $request->input('possibleValues')));

        return redirect()->action('Control\AttributeController@formAdd');

    }

    public function destroy($id)
    {
        $property = Property::find($id);
        if ($property) {
            if ($property->isTrashed())
                $property->restore();
            else
                $property->destroy();
        }
    }
}
