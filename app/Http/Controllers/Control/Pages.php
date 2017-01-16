<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;

class Pages extends Controller
{
    public function index()
    {
        return view('control.main');
    }

    public function meals()
    {
        return view('control.catalog.meals');
    }
}
