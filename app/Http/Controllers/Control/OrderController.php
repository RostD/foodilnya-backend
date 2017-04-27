<?php

namespace App\Http\Controllers\Control;

use App\Models\OrderModel;
use App\Order\Client;
use App\Order\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        if (Gate::denies('order-see'))
            abort(401);

        $filterName = $request->input('name');
        $filterLogin = $request->input('login');

        $data['orders'] = Order::all();

        return view('control.order.order.orders', $data);
    }

    public function formAdd()
    {
        if (Gate::denies('order-add'))
            abort(401);

        $data['clients'] = Client::all(false);
        return view('control.order.order.formAdd', $data);
    }

    public function add(Request $request)
    {
        if (Gate::denies('order-add'))
            abort(401);

        $this->validate($request, [
            'client' => 'required:exists:users,id',
            'date' => 'required|date|after:+5 day',
            'address' => 'required'
        ]);

        $client_id = $request->input('client');
        $date = $request->input('date');
        $address = $request->input('address');

        DB::beginTransaction();

        Order::create($client_id, $date, $address);

        DB::commit();

        return back();
    }
}
