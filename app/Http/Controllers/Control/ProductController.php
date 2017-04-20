<?php

namespace App\Http\Controllers\Control;

use App\MaterialValue\Product;
use App\MaterialValue\Tag;
use App\MaterialValue\Unit;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function products(Request $request)
    {
        if (Gate::denies('product-see'))
            abort(401);

        $filterName = $request->input('name');
        $filterTags = $request->input('tags');

        $products = MaterialValue::products()->withTrashed();

        if ($filterName)
            $products->where('name', 'like', '%' . $filterName . '%');

        if ($filterTags) {
            foreach ($filterTags as $tag) {
                $products->whereHas('attributes', function ($query) use ($tag) {
                    $query->where('attribute_id', '=', $tag);
                });
            }
        }

        $data['products'] = [];
        foreach ($products->get() as $model) {
            $data['products'][] = new Product($model);
        }


        $data['tags'] = Tag::allUsedDishesTags();
        return view('control.nomenclature.product.products', $data);
    }

    public function formAdd()
    {
        if (Gate::denies('product-add'))
            abort(401);

        $data['units'] = Unit::all(false);
        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.product.formAdd', $data);
    }

    public function add(Request $request)
    {
        if (Gate::denies('product-add'))
            abort(401);

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

        $product = Product::create($request->input('name'), $request->input('unit'));

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                $product->addTag($tag);
            }
        }

        DB::commit();

        return back();
    }

    public function formEdit($id)
    {
        if (Gate::denies('product-edit'))
            abort(401);

        $data['product'] = Product::find($id);

        if (!$data['product'])
            abort(404);

        $data['tags'] = Tag::all(false);
        return view('control.nomenclature.product.formEdit', $data);


    }

    public function edit(Request $request, $id)
    {
        if (Gate::denies('product-delete'))
            abort(401);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $product = Product::find($id);

        if ($product) {
            $tags = $request->input('tags');
            $newTags = trim($request->input('newTags'));

            DB::beginTransaction();

            if (!empty($newTags)) {
                foreach (explode(',', $newTags) as $newTag) {
                    $t = Tag::create($newTag);
                    if ($t) $tags[] = $t->id;
                }
            }

            $product->name = $request->input('name');

            if (!empty($tags)) {
                $product->replaceTags($tags);
            }
            DB::commit();

            return back();
        }
        abort(404);


    }

    public function delete(Request $request, $id)
    {
        if (Gate::denies('product-delete'))
            abort(401);

        $product = Product::find($id);

        if ($product) {
            $product->destroy();
            return response('', 200);
        }
        abort(404);
    }
}
