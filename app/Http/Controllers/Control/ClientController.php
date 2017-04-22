<?php

namespace App\Http\Controllers\Control;

use App\Order\Client;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ClientController extends Controller
{
    public function clients(Request $request)
    {
        if (Gate::denies('client-see'))
            abort(401);

        $filterName = $request->input('name');

        $clients = User::clients()->withTrashed();

        if ($filterName)
            $clients->where('name', 'like', '%' . $filterName . '%');


        $data['clients'] = [];
        foreach ($clients->get() as $model) {
            $data['clients'][] = new Client($model);
        }


        return view('control.order.client.clients', $data);
    }
}
