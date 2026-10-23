<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if ($tenant) {
            if ($tenant->status === 'suspended') {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Your company workspace has been suspended. Please contact the administrator.',
                    ], 403);
                }

                return response()->view('errors.tenant-suspended', [
                    'tenant' => $tenant,
                ], 403);
            }

            if ($tenant->status === 'inactive') {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This workspace is currently inactive.',
                    ], 403);
                }

                return response()->view('errors.tenant-inactive', [
                    'tenant' => $tenant,
                ], 403);
            }

            if ($tenant->isTrialExpired()) {
                $msg = 'Your trial period has expired. Please upgrade your subscription plan to continue accessing your workspace.';
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                    ], 403);
                }

                return response()->view('errors.tenant-expired', [
                    'tenant' => $tenant,
                    'reason' => 'trial',
                    'message' => $msg,
                ], 403);
            }

            if ($tenant->isPlanExpired()) {
                $msg = 'Your subscription plan has expired. Please renew your plan to continue accessing your workspace.';
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                    ], 403);
                }

                return response()->view('errors.tenant-expired', [
                    'tenant' => $tenant,
                    'reason' => 'plan',
                    'message' => $msg,
                ], 403);
            }
        }

        return $next($request);
    }
}
