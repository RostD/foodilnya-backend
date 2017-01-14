<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;

class Pages extends Controller
{
    public function menuBuilder()
    {
        return view('control.menubuilder.main');
    }
}
