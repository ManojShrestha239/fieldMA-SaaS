<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class marketingAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->user_type == 'marketing' || Auth::user()->user_type == 'ASM') {
            return $next($request);
        }
        abort(403);
    }
}
