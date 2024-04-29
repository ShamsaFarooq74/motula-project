<?php

namespace App\Http\Middleware;

use Closure;

class is_loggin
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
            if(auth()->user()->role == 2){
                // return $next($request);
                return redirect(url('/all-courses'));
            }
            elseif(auth()->user()->role == 1){
                return redirect(url('features'));

            } elseif(auth()->user()->role == 3 ){

                return redirect(url('category'));
            }
        }
        else{
              return $next($request);
        }

    }
}
