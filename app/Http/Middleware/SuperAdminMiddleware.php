<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_type == 'super' || Auth::user()->id == Auth::user()->id) {
            // dd(Auth::user()->id);
            return $next($request);
        }
        abort(403);

    }
}
