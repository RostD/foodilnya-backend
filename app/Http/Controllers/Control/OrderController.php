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

    public function formEdit($id)
    {
        if (Gate::denies('order-edit'))
            abort(401);

        $data['order'] = Order::find($id);
        return view('control.order.order.formEdit', $data);
    }

    public function formAddMaterialStringDish($orderId)
    {
        if (Gate::denies('order-edit'))
            abort(401);

        $order = Order::find($orderId);

        if ($order) {
            $dishes = Order::allNotUsedDishes($order->id);

            return view('control/order/order/formAddMaterialString', ['order' => $order,
                'materials' => $dishes]);
        }

        abort(404);
    }

    public function formAddMaterialStringIngredient($orderId)
    {
        if (Gate::denies('order-edit'))
            abort(401);

        $order = Order::find($orderId);

        if ($order) {
            $ingredients = Order::allNotUsedIngredients($order->id);

            return view('control/order/order/formAddMaterialString', ['order' => $order,
                'materials' => $ingredients]);
        }

        abort(404);
    }

    public function formAddMaterialStringAdaptation($orderId)
    {
        if (Gate::denies('order-edit'))
            abort(401);

        $order = Order::find($orderId);

        if ($order) {
            $adaptations = Order::allNotUsedAdaptations($order->id);

            return view('control/order/order/formAddMaterialString', ['order' => $order,
                'materials' => $adaptations]);
        }

        abort(404);
    }

    public function addMaterialString(Request $request)
    {
        if (Gate::denies('order-edit'))
            abort(401);

        $this->validate($request, [
            'material' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'order' => 'required',
        ]);

        $material = $request->input('material');
        $quantity = trim($request->input('quantity'));
        $unit = $request->input('unit');
        $order = Order::find($request->input('order'));

        if ($order) {
            $order->addMaterialString($material, $quantity, (int)$unit);

            return back();
        }
        abort(400);
    }
}
