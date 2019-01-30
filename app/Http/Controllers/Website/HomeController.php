<?php

namespace App\Http\Controllers\Website;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view("Website/index");
    }
    public function info()
    {
        return view("Website/info");
    }
}
