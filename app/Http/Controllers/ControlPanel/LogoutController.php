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
        $accountType = Cookie::get("EXAM_SYSTEM_ACCOUNT_TYPE");
        switch ($accountType)
        {
            case (AccountType::MANAGER):
                $account = Admin::where("session", Cookie::get("EXAM_SYSTEM_ACCOUNT_SESSION"))
                    ->first();
                break;

            case (AccountType::LECTURER):
                $account = Lecturer::where("session", Cookie::get("EXAM_SYSTEM_ACCOUNT_SESSION"))
                    ->first();
                break;

            default: $account = false;
        }

        if (!$account)
            return redirect("/control-panel");

        $account->session = null;
        $account->save();

        session()->remove("EXAM_SYSTEM_ACCOUNT_ID");
        session()->remove("EXAM_SYSTEM_ACCOUNT_NAME");
        session()->remove("EXAM_SYSTEM_ACCOUNT_USERNAME");
        session()->remove("EXAM_SYSTEM_ACCOUNT_TYPE");
        session()->remove("EXAM_SYSTEM_ACCOUNT_STATE");
        session()->remove("EXAM_SYSTEM_ACCOUNT_SESSION");
        session()->save();

        $cookie = Cookie::forget("EXAM_SYSTEM_ACCOUNT_SESSION");
        $cookie = Cookie::forget("EXAM_SYSTEM_ACCOUNT_TYPE");

        return redirect("/control-panel")->withCookie($cookie);
    }
}
