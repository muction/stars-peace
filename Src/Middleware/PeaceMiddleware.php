<?php

namespace Stars\Peace\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class PeaceMiddleware
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
        if( !Auth::check() ){
            return redirect( route('rotate.auth.login.page') );
        }
        return $next($request);
    }
}
