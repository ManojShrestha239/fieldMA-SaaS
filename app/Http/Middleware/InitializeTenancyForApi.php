<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyForApi
{
    protected InitializeTenancyByDomain $domainResolver;

    public function __construct(InitializeTenancyByDomain $domainResolver)
    {
        $this->domainResolver = $domainResolver;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenancy()->initialized) {
            return $next($request);
        }

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
                    'status' => 'error',
                    'message' => "Tenant workspace '{$tenantIdentifier}' was not found.",
                ], 404);
            }

            tenancy()->initialize($tenant);
            return $next($request);
        }

        // 2. Check for tenant_id embedded in JWT Bearer token
        $bearerToken = $request->bearerToken();
        if ($bearerToken) {
            try {
                $payload = auth('api')->setToken($bearerToken)->getPayload();
                $tokenTenantId = $payload->get('tenant_id');
                if (!empty($tokenTenantId)) {
                    $tenant = Tenant::where('id', $tokenTenantId)
                        ->orWhere('slug', $tokenTenantId)
                        ->first();
                    if ($tenant) {
                        tenancy()->initialize($tenant);
                        return $next($request);
                    }
                }
            } catch (\Throwable $e) {
                // If token is invalid or expired, continue to domain resolver or let auth guard handle it
            }
        }

        // 3. Fall back to Domain or Subdomain resolution
        try {
            return $this->domainResolver->handle($request, $next);
        } catch (\Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Tenant workspace could not be identified. Please specify a valid tenant domain or pass the X-Tenant header.',
            ], 404);
        }
    }
}
