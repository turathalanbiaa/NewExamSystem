<?php

namespace App\Http\Middleware;

use App\Enums\AccountType;
use App\Models\Admin;
use App\Models\Lecturer;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\DocBlock;

class AutoLoginAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd(Cookie::get("EXAM_SYSTEM_ACCOUNT_SESSION"));

        if (session()->has("EXAM_SYSTEM_ACCOUNT_SESSION"))
            return $next($request);

        if ((Cookie::has("EXAM_SYSTEM_ACCOUNT_SESSION")) && (Cookie::has("EXAM_SYSTEM_ACCOUNT_TYPE")))
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

            if ($account)
            {
                session()->put('EXAM_SYSTEM_ACCOUNT_ID', $account->id);
                session()->put('EXAM_SYSTEM_ACCOUNT_NAME' , $account->name);
                session()->put('EXAM_SYSTEM_ACCOUNT_USERNAME' , $account->username);
                session()->put('EXAM_SYSTEM_ACCOUNT_STATE' , $account->state);
                session()->put('EXAM_SYSTEM_ACCOUNT_SESSION', $account->session);
                session()->put('EXAM_SYSTEM_ACCOUNT_TYPE', $accountType);
                session()->save();

                return $next($request);
            }
        }

        return redirect("/control-panel/login");
    }
}
