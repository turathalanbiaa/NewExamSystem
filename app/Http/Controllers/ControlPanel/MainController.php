<?php

namespace App\Http\Controllers\ControlPanel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class MainController extends Controller
{
    public function index()
    {
        return view("ControlPanel.index");
    }
}
