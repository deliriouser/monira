<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class isSatker
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
        //check auth
        if(!Auth::check()){
            return redirect('/');
        }
        //level satker
        if(Auth::user()->level_id ==3) {
            return $next($request);
        } else {
            return redirect()->route('login');
        }
    }

}
