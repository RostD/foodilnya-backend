<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Warehouse\WarehouseBase;
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
}
