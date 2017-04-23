<?php

namespace App\Http\Controllers\Control;

use App\Models\OrderModel;
use App\Order\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
