<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->type == 0){
            $redirectPath = "/admin/home";
        }else{
            $redirectPath = "/cadastre-se";
        }
        if (Auth::guard($guard)->check()) {
            return redirect($redirectPath);
        }

        return $next($request);
    }
}
