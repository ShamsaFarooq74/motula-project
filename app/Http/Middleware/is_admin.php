<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class is_admin
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
    if(auth()->user()){
        if(auth()->user()->role == 1){
            return $next($request);
            return redirect(url('features'));

        }else if(auth()->user()->role == 3){
            return $next($request);
            return redirect(url('category'));
        }else{
            return redirect(url('/'));
        }
    }
    else{
        return redirect(url('/'));
    }

    }
}
