<?php

namespace App\Http\Middleware;

use App\Models\Mobileuser;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthenticateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->header('authorization');
        if ($bearerToken && Str::start($bearerToken, 'Bearer ')) {
            $token = substr($bearerToken, 7); // Remove 'Bearer ' prefix
            if (! Auth::check()) {
                $user = Mobileuser::where('remember_token', $token)->first();
                if ($user) {
                    Auth::setUser($user);
                }
            }

            return $next($request);
        }

        return $next($request);
    }
}
