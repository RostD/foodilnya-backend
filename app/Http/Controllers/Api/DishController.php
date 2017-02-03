<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\JsonResponse;
use App\MaterialValue\Dish;

class DishController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();

        if (!$dishes)
            return JsonResponse::gen(404);

        $data = [];
        foreach ($dishes as $dish) {
            $data[] = $dish->toArray();
        }
        return JsonResponse::gen(200, $data);


    }

    public function show($dish_id)
    {

    }
}
