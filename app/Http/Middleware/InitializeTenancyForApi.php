<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyForApi
{
    protected InitializeTenancyByDomainOrSubdomain $domainResolver;

    public function __construct(InitializeTenancyByDomainOrSubdomain $domainResolver)
    {
        $this->domainResolver = $domainResolver;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check for X-Tenant header or query parameter
        $tenantIdentifier = $request->header('X-Tenant') ?? $request->query('tenant');

        if (!empty($tenantIdentifier)) {
            // Find tenant by ID or slug
            $tenant = Tenant::where('id', $tenantIdentifier)
                ->orWhere('slug', $tenantIdentifier)
                ->first();

            if (!$tenant) {
                return response()->json([
                    'success' => false,
                    'message' => "Tenant workspace '{$tenantIdentifier}' was not found.",
                ], 404);
            }

            tenancy()->initialize($tenant);
            return $next($request);
        }

        // 2. Otherwise fall back to Domain or Subdomain resolution
        return $this->domainResolver->handle($request, $next);
    }
}
