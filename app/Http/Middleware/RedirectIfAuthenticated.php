<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
        if(auth()->user()) {
            if (auth()->user()->role == 1) {
                if (Auth::guard($guard)->check()) {
                    return redirect('/features');
                }
            }elseif(auth()->user()->role == 2){
                return redirect(url('/all-courses'));
            }elseif(auth()->user()->role == 3){
                return redirect(url('/category'));
            }

        }else{
        return $next($request);
        }
    }
}
