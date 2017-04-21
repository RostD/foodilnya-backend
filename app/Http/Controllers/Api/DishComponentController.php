<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\JsonResponse;
use App\MaterialValue\Adaptation;
use App\MaterialValue\DishComponent;
use App\MaterialValue\Ingredient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DishComponentController extends Controller
{
    public function getAvailableUnits($id)
    {
        //TODO if (Gate::denies('ingredient-see')) abort(403);

        $model = Ingredient::find($id);

        if (!$model)
            $model = Adaptation::find($id);

        if ($model) {
            $data = [];
            foreach ($model->getAvailableUnits() as $availableUnit) {
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
