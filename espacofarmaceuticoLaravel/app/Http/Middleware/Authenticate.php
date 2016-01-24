<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
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
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }else{
            if(Auth::getUser() and Auth::getUser()->type == 1){
                if(substr($request->path(), 0, 5) == 'admin'){
                    return redirect('/');
                }else{
                    return $next($request);
                }
            }else{
                return $next($request);
            }
        }
        //return $next($request);
    }
}
