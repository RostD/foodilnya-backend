<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\JsonResponse;
use App\MaterialValue\Adaptation;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\MaterialValue\Product;
use App\Models\AttributeOfMaterialValue;

class TagsController extends Controller
{
    public function getUsed(string $material_type = null)
    {
        $type_id = null;
        $http_status = 404;

        switch ($material_type) {
            case 'dish':
                $type_id = Dish::type_id;
                break;
            case 'product':
                $type_id = Product::type_id;
                break;
            case 'adaptation':
                $type_id = Adaptation::type_id;
                break;
            case 'ingredient':
                $type_id = Ingredient::type_id;
                break;
            default;
        }

        $tags = AttributeOfMaterialValue::usedTags($type_id)->get();

        if ($tags)        
            $http_status = 200;


        return JsonResponse::gen($http_status, $tags);
    }
}
