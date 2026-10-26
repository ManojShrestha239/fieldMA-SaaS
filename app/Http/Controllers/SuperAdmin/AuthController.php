<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.dashboard');
        }

        return view('super-admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('super_admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $admin = Auth::guard('super_admin')->user();
            if (!$admin->is_active) {
                Auth::guard('super_admin')->logout();
                return back()->withErrors(['email' => 'Your account has been deactivated.']);
            }
            $request->session()->forget('url.intended');

            // dd('1', "Login successful. Redirecting to dashboard...");
            return redirect()->route('super-admin.dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login');
    }
}
