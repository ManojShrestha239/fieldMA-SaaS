<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $tenant = tenant();

        if ($tenant && method_exists($tenant, 'hasFeature') && !$tenant->hasFeature($feature)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'code' => 'FEATURE_DISABLED',
                    'message' => "The module '{$feature}' is not included in your company's subscription plan.",
                ], 403);
            }

            if (view()->exists('errors.feature-disabled')) {
                return response()->view('errors.feature-disabled', [
                    'feature' => $feature,
                    'featureName' => ucwords(str_replace(['_', '-'], ' ', $feature)),
                ], 403);
            }

            return redirect()->route('dashboard')->with('error', "Your current subscription plan does not include access to the '{$feature}' feature.");
        }
        return $next($request);
    }
}
