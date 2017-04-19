<?php

namespace App\Http\Controllers\Control;

use App\MaterialValue\Adaptation;
use App\MaterialValue\Tag;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

    public function formAdd()
    {
        if (Gate::denies('adaptation-add'))
            abort(401);

        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.adaptation.formAdd', $data);
    }

    public function add(Request $request)
    {
        if (Gate::denies('adaptation-add'))
            abort(401);

        $this->validate($request, [
            'name' => 'required'
        ]);

        $tags = $request->input('tags');
        $newTags = trim($request->input('newTags'));

        DB::beginTransaction();

        if (!empty($newTags)) {
            foreach (explode(',', $newTags) as $newTag) {
                $t = Tag::create($newTag);
                if ($t) $tags[] = $t->id;
            }
        }

        $adaptation = Adaptation::create($request->input('name'));

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $adaptation->addTag($tag);
            }
        }

        DB::commit();

        return back();
    }

    public function formEdit($id)
    {
        if (Gate::denies('adaptation-edit'))
            abort(401);

        $data['adaptation'] = Adaptation::find($id);
        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.adaptation.formEdit', $data);
    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('adaptation-edit'))
            abort(401);

        $this->validate($request, [
            'name' => 'required'
        ]);

        $adaptation = Adaptation::find($id);

        if ($adaptation) {
            $tags = $request->input('tags');
            $newTags = trim($request->input('newTags'));

            DB::beginTransaction();

            if (!empty($newTags)) {
                foreach (explode(',', $newTags) as $newTag) {
                    $t = Tag::create($newTag);
                    if ($t) $tags[] = $t->id;
                }
            }

            $adaptation->name = $request->input('name');

            if (!empty($tags)) {
                $adaptation->replaceTags($tags);
            }
            DB::commit();

            return back();
        }
        abort(404);
    }

    public function delete(Request $request, $id)
    {
        if (Gate::denies('adaptation-delete'))
            abort(401);

        $adaptation = Adaptation::find($id);

        if ($adaptation) {
            $adaptation->destroy();
            return response('', 200);
        }
        abort(404);
    }
}
