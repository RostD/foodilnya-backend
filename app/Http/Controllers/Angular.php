<?php

namespace App\Http\Controllers;

class Angular extends Controller
{
    public function getTemplate($tmp_src)
    {
        return view('backend.' . $tmp_src);
    }
}
