<?php

namespace App\Http\Controllers\Control;

use App\MaterialValue\Adaptation;
use App\MaterialValue\Tag;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AdaptationController extends Controller
{
    public function adaptations(Request $request)
    {
        if (Gate::denies('adaptation-see'))
            abort(401);

        $filterName = $request->input('name');
        $filterTags = $request->input('tags');

        $adaptations = MaterialValue::adaptations()->withTrashed();

        if ($filterName)
            $adaptations->where('name', 'like', '%' . $filterName . '%');

        if ($filterTags) {
            foreach ($filterTags as $tag) {
                $adaptations->whereHas('attributes', function ($query) use ($tag) {
                    $query->where('attribute_id', '=', $tag);
                });
            }
        }

        $data['adaptations'] = [];
        foreach ($adaptations->get() as $model) {
            $data['adaptations'][] = new Adaptation($model);
        }


        $data['tags'] = Tag::allUsedAdaptationsTags();
        return view('control.nomenclature.adaptation.adaptations', $data);
    }
}
