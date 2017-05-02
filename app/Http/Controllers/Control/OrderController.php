<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Order\Client;
use App\Order\Order;
use App\Order\OrderMaterialString;
use App\Warehouse\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        if (Gate::denies('order-see'))
            abort(401);

        Register::recountBalance(1);
        
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

    public function edit(Request $request, $id)
    {
        if (Gate::denies('order-edit'))
            abort(401);
        $confirmed = (bool)$request->input('confirmed');
        $equipped = (bool)$request->input('equipped');
        $closed = (bool)$request->input('closed');

        $order = Order::find($id);

        if ($order) {

            DB::beginTransaction();

            $order->confirmed = $confirmed;
            $order->equipped = $equipped;
            $order->done = $closed;
            DB::commit();

            return back();
        }
        abort(404);
    }

    public function delete(Request $request, $id)
    {
        if (Gate::denies('order-delete'))
            abort(401);

        $order = Order::find($id);

        if ($order) {
            $order->destroy();
            return response('', 200);
        }
        abort(404);
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
        if (Gate::denies('order-editStrings'))
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
        if (Gate::denies('order-editStrings'))
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
        if (Gate::denies('order-editStrings'))
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
        if (Gate::denies('order-editStrings'))
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

    public function formEditMaterialString($orderId, $materialId)
    {
        if (Gate::denies('order-editStrings'))
            abort(401);

        $order = Order::find($orderId);

        if ($order) {
            if ($order->issetMaterialString($materialId))
                return view('control/order/order/formEditMaterialString', ['order' => $order,
                    'materialString' => OrderMaterialString::find($order->id, $materialId)]);
        }
        abort(404);
    }

    public function editMaterialString(Request $request)
    {
        $this->addMaterialString($request);

        return back();
    }

    public function removeMaterialString(Request $request, $orderId, $material)
    {
        if (Gate::denies('order-editStrings'))
            abort(403);

        $order = Order::find((int)$orderId);

        if ($order) {
            $order->removeMaterialString((int)$material);
        }
        return response('', 200);
    }
}
