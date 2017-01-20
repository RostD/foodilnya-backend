<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;

class Pages extends Controller
{
    public function index()
    {
        return view('backend.control.main');
    }

    public function dishes()
    {
        return view('backend.control.catalog.dishes');
    }
}
