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
        if (session()->has("EXAM_SYSTEM_ACCOUNT_SESSION"))
            return redirect("/control-panel");

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
            "accountType" => "required|integer|between:1,2"
        ], [
            "username.required"    => "يرجى ادخال اسم المستخدم.",
            "password.required"    => "يرجى ادخال كلمة المرور.",
            "accountType.required" => "يرجى اختيار نوع الحساب.",
            'accountType.integer'  => 'يجب اختيار نوع الحساب اما 1 او 2.',
            'accountType.between'  => 'يجب اختيار نوع الحساب اما مدير او استاذ.'
        ]);

        $username = Input::get("username");
        $password = md5(Input::get("password"));
        $accountType = Input::get("accountType");

        switch ($accountType)
        {
            case (AccountType::MANAGER):
                $account = Admin::where("username", $username)
                    ->where("password", $password)
                    ->first();
                break;

            case (AccountType::LECTURER):
                $account = Lecturer::where("username", $username)
                    ->where("password", $password)
                    ->first();
                break;

            default: $account = false;
        }

        if (!$account)
            return redirect("/control-panel/login")
                ->with('ErrorLoginMessage', "فشل تسجيل الدخول !!! أعد المحاولة مرة أخرى");

        /**
         * Store login from multi devises.
         */
        if (is_null($account->session))
            $account->session = md5(uniqid());
        $account->save();

        session()->put('EXAM_SYSTEM_ACCOUNT_ID', $account->id);
        session()->put('EXAM_SYSTEM_ACCOUNT_NAME', $account->name);
        session()->put('EXAM_SYSTEM_ACCOUNT_USERNAME', $account->username);
        session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $account->state);
        session()->put('EXAM_SYSTEM_ACCOUNT_SESSION', $account->session);
        session()->put('EXAM_SYSTEM_ACCOUNT_TYPE', $accountType);
        session()->save();

        //Session for one year (525600 minutes)
        return redirect("/control-panel")
            ->withCookie(cookie('EXAM_SYSTEM_ACCOUNT_SESSION', $account->session, 525600))
            ->withCookie(cookie('EXAM_SYSTEM_ACCOUNT_TYPE', $accountType, 525600));
    }
}
