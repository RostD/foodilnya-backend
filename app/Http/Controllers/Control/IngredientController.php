<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\MaterialValue\Ingredient;
use App\MaterialValue\Tag;
use App\MaterialValue\Unit;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class IngredientController extends Controller
{
    public function ingredients(Request $request)
    {
        if (Gate::denies('ingredient-see'))
            abort(403);

        $filterName = $request->input('name');
        $filterTags = $request->input('tags');

        $ingredient = MaterialValue::ingredients()->withTrashed();

        if ($filterName)
            $ingredient->where('name', 'like', '%' . $filterName . '%');

        if ($filterTags) {
            foreach ($filterTags as $tag) {
                $ingredient->whereHas('attributes', function ($query) use ($tag) {
                    $query->where('attribute_id', '=', $tag);
                });
            }
        }

        $data['ingredients'] = [];
        foreach ($ingredient->get() as $model) {
            $data['ingredients'][] = new Ingredient($model);
        }


        $data['tags'] = Tag::allUsedIngredientsTags();
        return view('control.nomenclature.ingredient.ingredients', $data);
    }

    public function formAdd()
    {
        if (Gate::denies('ingredient-add'))
            abort(403);

        $data['units'] = Unit::all(false);
        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.ingredient.formAdd', $data);
    }

    public function add(Request $request)
    {
        if (Gate::denies('ingredient-add'))
            abort(403);

        $this->validate($request, [
            'name' => 'required',
            'unit' => 'required| exists:units,id'
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

        $ingredient = Ingredient::create($request->input('name'), $request->input('unit'));

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $ingredient->addTag($tag);
            }
        }

        DB::commit();

        return redirect()->action('Control\IngredientController@formAdd');
    }

    public function formEdit($id)
    {
        if (Gate::denies('ingredient-edit'))
            abort(403);

        $data['ingredient'] = Ingredient::find($id);

        if (!$data['ingredient'])
            abort(404);

        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.ingredient.formEdit', $data);


    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('ingredient-delete'))
            abort(403);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            $tags = $request->input('tags');
            $newTags = trim($request->input('newTags'));

            DB::beginTransaction();

            if (!empty($newTags)) {
                foreach (explode(',', $newTags) as $newTag) {
                    $t = Tag::create($newTag);
                    if ($t) $tags[] = $t->id;
                }
            }

            $ingredient->name = $request->input('name');

            if (!empty($tags)) {
                $ingredient->replaceTags($tags);
            }
            DB::commit();

            return redirect()->action('Control\IngredientController@formEdit', ['id' => $ingredient->id]);
        }
        abort(404);


    }

    public function delete(Request $request, $id)
    {
        if (Gate::denies('ingredient-delete'))
            abort(403);

        $ingredient = Ingredient::find($id);

        if ($ingredient) {
            $ingredient->destroy();
            return response('', 200);
        }
        abort(404);
    }

}
