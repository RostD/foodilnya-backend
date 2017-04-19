<?php

namespace App\Http\Controllers\Control;

use App\MaterialValue\Product;
use App\MaterialValue\Tag;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
