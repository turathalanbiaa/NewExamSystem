<?php

namespace App\Http\Controllers\ControlPanel;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    public function login() {
        if (Cookie::has("EXAM_SYSTEM_ADMIN_SESSION")) {
            $admin = Admin::where("session", "=", Cookie::get("EXAM_SYSTEM_ADMIN_SESSION"))->first();

            if (!$admin)
                return view("/control-panel/login");

            session()->put('EXAM_SYSTEM_ADMIN_SESSION', $admin->session);
            session()->save();

            return redirect("/control-panel");
        }

        return view("ControlPanel/login");
    }

    public function loginValidate(Request $request) {
        $rules = [
            "username" => "required",
            "password" => "required"
        ];

        $rulesMessage = [
            "username.required" => "يرجى ادخال اسم المستخدم.",
            "password.required" => "يرجى ادخال كلمة المرور."
        ];

        $this->validate($request, $rules, $rulesMessage);

        $username = Input::get("username");
        $password = md5(Input::get("password"));

        $admin = Admin::where("username","=",$username)->where("password","=",$password)->first();

        if (!$admin)
            return redirect("/control-panel/login")->with('ErrorLoginMessage', "فشل تسجيل الدخول !!! أعد المحاولة مرة أخرى");

        $session = md5(uniqid());
        $admin->session = $session;
        $admin->save();

        session()->put('EXAM_SYSTEM_ADMIN_SESSION' , $admin->session);

        return redirect("/control-panel")->withCookie(cookie('EXAM_SYSTEM_ADMIN_SESSION' , $admin->session , 1000000000));
    }

}
