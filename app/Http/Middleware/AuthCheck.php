<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        dd($request->all());
        if (Auth::guard($guard)->check()) {
            if (strtolower((string) Auth::user()->status) !== 'active') {
                Auth::logout();
                return redirect('/login')->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
            }
        }
        return $next($request);
    }
}
