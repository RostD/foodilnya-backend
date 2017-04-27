<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\JsonResponse;
use App\Order\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function getAddress($id)
    {
        $client = Client::find($id);
        if ($client) {
            return JsonResponse::gen(200, ['address' => $client->address]);
        }
        abort(404);
    }
}
