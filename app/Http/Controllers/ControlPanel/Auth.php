<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountState;
use App\Enums\AccountType;
use App\Http\Controllers\Controller;

class Auth extends Controller
{
    /**
     * Check Auth
     */
    public static function check()
    {
        self::isLoggedIn();
        self::isOpenAccount();
        self::hasPermission();
    }

    /**
     * Check account is logged in or not
     */
    private static function isLoggedIn()
    {
        if (!session()->has("EXAM_SYSTEM_ACCOUNT_TOKEN"))
            abort(302, '', ['Location' => "/control-panel/login"]);
    }

    /**
     * Check account is open or not
     */
    private static function isOpenAccount()
    {
        if (session()->get("EXAM_SYSTEM_ACCOUNT_STATE") == AccountState::CLOSE)
            abort(302, '', ['Location' => "/control-panel/close"]);
    }

    /**
     * Check account permission
     */
    private static function hasPermission()
    {
        $accountType = session()->get("EXAM_SYSTEM_ACCOUNT_TYPE");

        if (($accountType == AccountType::MANAGER) && (session()->get("EXAM_SYSTEM_ACCOUNT_ID") != 1))
        {
            if (request()->is("control-panel/admins*"))
                abort(404);
        }

        if($accountType == AccountType::LECTURER)
        {
            if (request()->is("control-panel/admins*") || request()->is("control-panel/lecturer*") || request()->is("control-panel/courses/*"))
                abort(404);
        }
    }
}
