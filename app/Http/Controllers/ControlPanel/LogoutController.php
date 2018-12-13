<?php

namespace App\Http\Controllers\ControlPanel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    public function logout() {
        $admin = Admin::where("session", "=", Cookie::get("EXAM_SYSTEM_ADMIN_SESSION"))->first();

        if (!$admin)
            return redirect("/control-panel");

        $admin->session = null;
        $admin->save();

        session()->remove("EXAM_SYSTEM_ADMIN_SESSION");
        session()->save();

        $cookie = Cookie::forget("EXAM_SYSTEM_ADMIN_SESSION");

        return redirect("/control-panel")->withCookie($cookie);
    }
}
