<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    public function logout()
    {
        //Find account
        $accountType = Cookie::get("EXAM_SYSTEM_ACCOUNT_TYPE");
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

        //Redirect to main page if account is not found
        if (!$account)
            return redirect("/control-panel");

        //Remove remember token from account
        $account->remember_token = null;
        $account->save();

        //Remove session
        session()->remove("EXAM_SYSTEM_ACCOUNT_ID");
        session()->remove("EXAM_SYSTEM_ACCOUNT_NAME");
        session()->remove("EXAM_SYSTEM_ACCOUNT_USERNAME");
        session()->remove("EXAM_SYSTEM_ACCOUNT_STATE");
        session()->remove("EXAM_SYSTEM_ACCOUNT_TOKEN");
        session()->remove("EXAM_SYSTEM_ACCOUNT_TYPE");
        session()->save();

        //Remove cookies
        Cookie::queue(cookie()->forget("EXAM_SYSTEM_ACCOUNT_TOKEN"));
        Cookie::queue(cookie()->forget("EXAM_SYSTEM_ACCOUNT_TYPE"));

        //Redirect to login page
        return redirect("/control-panel/login");
    }
}
