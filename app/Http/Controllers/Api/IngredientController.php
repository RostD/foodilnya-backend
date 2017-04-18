<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\JsonResponse;
use App\MaterialValue\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class IngredientController extends Controller
{
    public function getAvailableUnits($id)
    {
        //TODO if (Gate::denies('ingredient-see')) abort(403);

        $ingredient = Ingredient::find($id);
        if ($ingredient) {
            $data = [];
            foreach ($ingredient->getAvailableUnits() as $availableUnit) {
                $data[] = [
                    'id' => $availableUnit->id,
                    'name' => $availableUnit->fullName,
                ];
            }
            return JsonResponse::gen(200, $data);
        }
        abort(404);

    }
}
