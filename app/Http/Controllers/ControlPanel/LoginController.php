<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Integer;

class LoginController extends Controller
{
    /**
     * Login
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function login()
    {
        //Redirect to main page
        if (session()->has("EXAM_SYSTEM_ACCOUNT_TOKEN"))
            return redirect("/control-panel");

        //Auto login
        if ((Cookie::has("EXAM_SYSTEM_ACCOUNT_TOKEN")) && (Cookie::has("EXAM_SYSTEM_ACCOUNT_TYPE")))
        {
            //Find account
            $accountType = (int) Cookie::get("EXAM_SYSTEM_ACCOUNT_TYPE");
            switch ($accountType)
            {
                case (AccountType::MANAGER):
                    $account = Admin::where("remember_token", Cookie::get("EXAM_SYSTEM_ACCOUNT_TOKEN"))
                        ->first();
                    break;
                case (AccountType::LECTURER):
                    $account = Lecturer::where("remember_token", Cookie::get("EXAM_SYSTEM_ACCOUNT_TOKEN"))
                        ->first();
                    break;
                default: $account = false;
            }

            //Account is not found
            if (!$account)
            {
                //Remove cookies
                Cookie::queue(cookie()->forget("EXAM_SYSTEM_ACCOUNT_TOKEN"));
                Cookie::queue(cookie()->forget("EXAM_SYSTEM_ACCOUNT_TYPE"));

                return view("ControlPanel.login");
            }

            //Make sessions
            session()->put('EXAM_SYSTEM_ACCOUNT_ID', $account->id);
            session()->put('EXAM_SYSTEM_ACCOUNT_NAME', $account->name);
            session()->put('EXAM_SYSTEM_ACCOUNT_USERNAME', $account->username);
            session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $account->state);
            session()->put('EXAM_SYSTEM_ACCOUNT_TOKEN', $account->remember_token);
            session()->put('EXAM_SYSTEM_ACCOUNT_TYPE', $accountType);
            session()->save();

            return redirect("/control-panel");
        }

        return view("ControlPanel.login");
    }

    /**
     * Login validation
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginValidate(Request $request)
    {
        //Validation
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

        //get inputs values
        $username = Input::get("username");
        $password = md5(Input::get("password"));
        $accountType = (int) Input::get("accountType");

        //Fina account
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

        //Redirect to login page with error message if account is not found
        if (!$account)
            return redirect("/control-panel/login")
                ->with('ErrorLoginMessage', "فشل تسجيل الدخول !!! أعد المحاولة مرة أخرى");

        //Store login from multi devises
        if (is_null($account->remember_token))
            $account->remember_token = md5(uniqid());
        $account->save();

        //Make sessions
        session()->put('EXAM_SYSTEM_ACCOUNT_ID', $account->id);
        session()->put('EXAM_SYSTEM_ACCOUNT_NAME', $account->name);
        session()->put('EXAM_SYSTEM_ACCOUNT_USERNAME', $account->username);
        session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $account->state);
        session()->put('EXAM_SYSTEM_ACCOUNT_TOKEN', $account->remember_token);
        session()->put('EXAM_SYSTEM_ACCOUNT_TYPE', $accountType);
        session()->save();

        //Make cookies
        Cookie::queue(cookie()->forever("EXAM_SYSTEM_ACCOUNT_TOKEN",$account->remember_token));
        Cookie::queue(cookie()->forever("EXAM_SYSTEM_ACCOUNT_TYPE",$accountType));

        //Redirect to main page
        return redirect("/control-panel");
    }
}
