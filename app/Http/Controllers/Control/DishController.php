<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\MaterialValue\Dish;
use App\MaterialValue\Tag;
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

        $data['tags'] = Tag::all();
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

        $dish = Dish::create($request->input('name'), $tags);

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
        $data['tags'] = Tag::all();
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

}