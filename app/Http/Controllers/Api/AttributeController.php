<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\JsonResponse;
use App\MaterialValue\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function getAttribute($id)
    {
        $attribute = Property::find($id);

        if ($attribute) {
            $data = [
                'id' => $attribute->id,
                'name' => $attribute->name,
                'fixedValues' => $attribute->isFixedValue(),
                'unit' => $attribute->getUnitName(),
            ];
            $possibleValues = [];
            foreach ($attribute->getPossibleValues() as $possibleValue) {
                $possibleValues[] = [
                    'value' => $possibleValue->value
                ];
            }

            $data['possibleValues'] = $possibleValues;

            return JsonResponse::gen(200, $data);
        }

        abort(404);

    }
}
