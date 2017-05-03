<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\OrderModel;
use App\Order\Order;
use App\Warehouse\Register;
use App\Warehouse\WarehouseBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WarehouseController extends Controller
{
    public function balanceWHBase()
    {
        if (Gate::denies('wh-base-seeBalance'))
            abort(401);
        $strings = (new WarehouseBase())->getBalance();

        return view('control.warehouse.base.balance', ['strings' => $strings]);
    }

    public function ordersForPicking(Request $request)
    {
        if (Gate::denies('order-see'))
            abort(401);

        Register::recountBalance(WarehouseBase::id);
        $day = $request->input('day');
        $orders = [];

        if ($day) {
            $models = OrderModel::whereDate('date', $day)
                ->where([
                    ['confirmed', true],
                    ['equipped', false],
                ])
                ->orderBy('date')
                ->get();

            foreach ($models as $model) {
                $orders[] = new Order($model);
            }
        }

        return view('control.warehouse.ordersForPicking', ['orders' => $orders]);
    }
}
