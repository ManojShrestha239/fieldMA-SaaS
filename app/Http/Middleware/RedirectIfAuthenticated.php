<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($guard === 'super_admin' || $request->is('super-admin*')) {
                return redirect()->route('super-admin.dashboard');
            }

            if (Auth::user() && (Auth::user()->user_type == 'super' || Auth::user()->user_type == 'marketing')) {
                return redirect('/dashboard');
            }
            if (Auth::user() && Auth::user()->user_type == 'account') {
                return redirect('/collection');
            }
            if (Auth::user() && Auth::user()->user_type == 'dispatch') {
                return redirect('/order');
            }
        }

        return $next($request);
    }
}
