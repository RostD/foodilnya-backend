<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\MaterialValue\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller
{
    public function units()
    {
        $data['units'] = Unit::all();
        return view('control.system.unit.units', $data);
    }

    public function formAdd()
    {
        return view('control.system.unit.formAdd');
    }

    public function formEdit($id)
    {
        $data['unit'] = Unit::find($id);

        if ($data['unit']) {
            return view('control.system.unit.formEdit', $data);
        }
        abort(404);
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'full_name' => 'required'
        ]);

        $unit = Unit::find($id);

        if ($unit) {
            DB::beginTransaction();
            $unit->name = $request->input('name');
            $unit->fullName = $request->input('full_name');
            DB::commit();

            return redirect()->action('Control\UnitController@formEdit', ['id' => $unit->id]);
        }
        abort(404);
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'name' => 'required'
        ]);

        Unit::create($request->input('name'), $request->input('full_name'));

        return redirect()->action('Control\UnitController@formAdd');
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);

        $unit->destroy();
    }
}
