<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function login()
    {
        if ((Cookie::has("EXAM_SYSTEM_ACCOUNT_SESSION")) && (Cookie::has("EXAM_SYSTEM_ACCOUNT_TYPE")))
        {
            $accountType = Cookie::has("EXAM_SYSTEM_ACCOUNT_TYPE");
            switch ($accountType)
            {
                case (AccountType::MANAGER):
                    $account = Admin::where("session", "=", Cookie::get("EXAM_SYSTEM_ACCOUNT_SESSION"))
                        ->first();
                    break;

                case (AccountType::LECTURER):
                    $account = Lecturer::where("session", "=", Cookie::get("EXAM_SYSTEM_ACCOUNT_SESSION"))
                        ->first();
                    break;

                default: $account = false;
            }

            if (!$account)
                return view("ControlPanel.login");

            session()->put('EXAM_SYSTEM_ACCOUNT_ID' , $account->id);
            session()->put('EXAM_SYSTEM_ACCOUNT_NAME' , $account->name);
            session()->put('EXAM_SYSTEM_ACCOUNT_STATE' , $account->state);
            session()->put('EXAM_SYSTEM_ACCOUNT_SESSION', $account->session);
            session()->put('EXAM_SYSTEM_ACCOUNT_TYPE' , $accountType);
            session()->save();

            return redirect("/control-panel");
        }

        return view("ControlPanel.login");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginValidate(Request $request)
    {
        $this->validate($request, [
            "username"    => "required",
            "password"    => "required",
            "accountType" => "required"
        ], [
            "username.required"    => "يرجى ادخال اسم المستخدم.",
            "password.required"    => "يرجى ادخال كلمة المرور.",
            "accountType.required" => "يرجى اختيار نوع الحساب."
        ]);

        $username = Input::get("username");
        $password = md5(Input::get("password"));
        $accountType = Input::get("accountType");

        switch ($accountType)
        {
            case (AccountType::MANAGER):
                $account = Admin::where("username", "=", $username)
                    ->where("password", "=", $password)
                    ->first();
                break;

            case (AccountType::LECTURER):
                $account = Lecturer::where("username", "=", $username)
                    ->where("password", "=", $password)
                    ->first();
                break;

            default: $account = false;
        }

        if (!$account)
            return redirect("/control-panel/login")
                ->with('ErrorLoginMessage', "فشل تسجيل الدخول !!! أعد المحاولة مرة أخرى");

        $account->session = md5(uniqid());
        $account->save();

        session()->put('EXAM_SYSTEM_ACCOUNT_ID' , $account->id);
        session()->put('EXAM_SYSTEM_ACCOUNT_NAME' , $account->name);
        session()->put('EXAM_SYSTEM_ACCOUNT_STATE' , $account->state);
        session()->put('EXAM_SYSTEM_ACCOUNT_SESSION' , $account->session);
        session()->put('EXAM_SYSTEM_ACCOUNT_TYPE' , $accountType);
        session()->save();

        //Session for one year (525600 minutes)
        return redirect("/control-panel")
            ->withCookie(cookie('EXAM_SYSTEM_ACCOUNT_SESSION' , $account->session , 525600))
            ->withCookie(cookie('EXAM_SYSTEM_ACCOUNT_TYPE' , $accountType , 525600));
    }
}
