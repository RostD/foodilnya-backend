<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\MaterialValue\Ingredient;
use App\MaterialValue\Tag;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
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


        $data['tags'] = Tag::allUsedDishesTags();
        return view('control.nomenclature.ingredient.ingredients', $data);
    }
}
