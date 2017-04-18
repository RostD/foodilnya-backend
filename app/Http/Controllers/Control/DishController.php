<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\MaterialValue\Tag;
use App\MaterialValue\Unit;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DishController extends Controller
{
    public function dishes(Request $request)
    {
        if (Gate::denies('dish-see'))
            abort(403);

        $filterName = $request->input('name');
        $filterTags = $request->input('tags');

        $dishes = MaterialValue::dishes()->withTrashed();

        if ($filterName)
            $dishes->where('name', 'like', '%' . $filterName . '%');

        if ($filterTags) {
            foreach ($filterTags as $tag) {
                $dishes->whereHas('attributes', function ($query) use ($tag) {
                    $query->where('attribute_id', '=', $tag);
                });
            }
        }

        $data['dishes'] = [];
        foreach ($dishes->get() as $model) {
            $data['dishes'][] = new Dish($model);
        }


        $data['tags'] = Tag::allUsedDishesTags();
        return view('control.nomenclature.dish.dishes', $data);
    }

    public function formAdd()
    {
        if (Gate::denies('dish-add'))
            abort(403);

        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.dish.formAdd', $data);
    }

    public function add(Request $request)
    {
        if (Gate::denies('dish-add'))
            abort(403);

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

        $dish = Dish::create($request->input('name'));

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $dish->addTag($tag);
            }
        }

        DB::commit();

        return redirect()->action('Control\DishController@formAdd');
    }

    public function formEdit($id)
    {
        if (Gate::denies('dish-edit'))
            abort(403);

        $data['dish'] = Dish::find($id);
        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.dish.formEdit', $data);
    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('dish-edit'))
            abort(403);

        $this->validate($request, [
            'name' => 'required'
        ]);

        $dish = Dish::find($id);

        if ($dish) {
            $tags = $request->input('tags');
            $newTags = trim($request->input('newTags'));

            DB::beginTransaction();

            if (!empty($newTags)) {
                foreach (explode(',', $newTags) as $newTag) {
                    $t = Tag::create($newTag);
                    if ($t) $tags[] = $t->id;
                }
            }

            $dish->name = $request->input('name');

            if (!empty($tags)) {
                $dish->replaceTags($tags);
            }
            DB::commit();

            return redirect()->action('Control\DishController@formEdit', ['id' => $dish->id]);
        }
        abort(404);
    }

    public function delete(Request $request, $id)
    {
        if (Gate::denies('dish-delete'))
            abort(403);

        $dish = Dish::find($id);

        if ($dish) {
            $dish->destroy();
            return response('', 200);
        }
        abort(404);
    }

    public function constructor(Request $request, $id)
    {
        if (Gate::denies('dish-edit'))
            abort(403);

        $dish = Dish::find($id);

        if ($dish) {
            return view('control.nomenclature.dish.configurator', ['dish' => $dish]);
        }
        abort(404);
    }

    public function formAddIngredient($dishId)
    {
        if (Gate::denies('dish-edit'))
            abort(403);

        $dish = Dish::find($dishId);

        if ($dish) {
            $ingredients = Ingredient::allNotUsed($dish->id);

            return view('control/nomenclature/dish/formAddIngredient', ['dish' => $dish,
                'ingredients' => $ingredients]);
        }
        //abort(404);
    }

    public function addIngredient(Request $request)
    {
        if (Gate::denies('dish-edit'))
            abort(403);

        $this->validate($request, [
            'ingredient' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'dish' => 'required',
        ]);

        $ingredient = $request->input('ingredient');
        $quantity = trim($request->input('quantity'));
        $unit = $request->input('unit');
        $dish = Dish::find($request->input('dish'));

        if ($dish) {
            $dish->addIngredient($ingredient, $quantity, (integer)$unit);
            return redirect()->action('Control\DishController@formAddIngredient', ['id' => $dish->id]);
        }
        abort(400);


    }

    public function removeIngredient(Request $request, $dish, $ingredient)
    {
        if (Gate::denies('dish-edit'))
            abort(403);

        $dish = Dish::find((int)$dish);

        if ($dish) {
            $dish->removeIngredient((int)$ingredient);
        }
        return response('', 200);
    }

}
