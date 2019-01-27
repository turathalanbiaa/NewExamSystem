<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class MainController extends Controller
{
    public function index()
    {
        Auth::check();
        return view("ControlPanel.index");
    }

    public function close()
    {
        return view("ControlPanel.close");
    }
}
