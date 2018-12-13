<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Support\Facades\Cookie;

class AutoLoginAdminMiddleware
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
        if (Cookie::has("EXAM_SYSTEM_ADMIN_SESSION")) {
            $admin = Admin::where("session", Cookie::get("EXAM_SYSTEM_ADMIN_SESSION"))->first();

            if ($admin) {
                session()->put('EXAM_SYSTEM_ADMIN_SESSION' , $admin->session);
                session()->save();

                return $next($request);
            }
        }

        return redirect("/control-panel/login");
    }
}
