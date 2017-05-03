<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\MaterialValue;
use App\Models\OrderModel;
use App\Order\Order;
use App\Report\ReportMaterialString;
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

    public function plannedCosts(Request $request)
    {
        if (Gate::denies('order-see') && Gate::denies('wh-base-seeBalance'))
            abort(401);
        $dayEnd = $request->input('dayEnd');
        $materials = [];

        if ($dayEnd) {
            $orders = [];
            $models = OrderModel::whereDate('date', '>=', date('Y-m-d'))
                ->whereDate('date', '<=', date('Y-m-d', strtotime($dayEnd)))
                ->where([
                    ['confirmed', true],
                    ['equipped', false],
                ])
                ->orderBy('date')
                ->get();

            if ($models)
                foreach ($models as $model) {
                    $orders[] = new Order($model);
                }


            foreach ($orders as $order) {
                foreach ($order->getMaterialValuesData()->array() as $item) {
                    if (array_key_exists($item->getMaterialId(), $materials))
                        $materials[$item->getMaterialId()]->quantity = $materials[$item->getMaterialId()]->quantity + (float)$item->getQuantity();
                    else {
                        $materials[$item->getMaterialId()] = new ReportMaterialString(MaterialValue::find($item->getMaterialId()), (float)$item->getQuantity());
                    }

                }
            }

            $whBase = new WarehouseBase();
            foreach ($materials as $key => $material) {
                $material->fact = $whBase->getMaterialBalance($key);
            }

        }
        return view('control.warehouse.base.plannedCosts', ['strings' => $materials]);
    }
}
