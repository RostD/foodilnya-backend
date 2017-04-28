<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\JsonResponse;
use App\MaterialValue\Adaptation;
use App\MaterialValue\Dish;
use App\MaterialValue\Ingredient;
use App\Models\MaterialValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MaterialController extends Controller
{
    public function getAvailableUnits($id)
    {
        //TODO if (Gate::denies('material-see')) abort(403);
        $model = MaterialValue::find($id);

        if ($model) {
            $material = false;

            if ($model->type_id == Dish::type_id)
                $material = new Dish($model);
            elseif ($model->type_id == Adaptation::type_id)
                $material = new Adaptation($model);
            elseif ($model->type_id == Ingredient::type_id)
                $material = new Ingredient($model);

            if ($material) {
                $data = [];
                foreach ($material->getAvailableUnits() as $availableUnit) {
                    $data[] = [
                        'id' => $availableUnit->id,
                        'name' => $availableUnit->fullName,
                    ];
                }
                return JsonResponse::gen(200, $data);
            }
        }

        abort(404);

    }
}
