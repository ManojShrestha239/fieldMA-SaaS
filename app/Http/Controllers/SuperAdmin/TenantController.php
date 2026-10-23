<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['domains', 'plan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('plan_id')) {
            $query->where('subscription_plan_id', $request->plan_id);
        }

        $tenants = $query->latest()->paginate(15);
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('super-admin.tenants.index', compact('tenants', 'plans'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $centralDomain = config('tenancy.central_domains')[0] ?? 'fieldma.com';

        return view('super-admin.tenants.create', compact('plans', 'centralDomain'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:tenants,id', 'unique:tenants,slug'],
            'custom_domain' => ['nullable', 'string', 'max:255', 'unique:domains,domain'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'status' => ['required', Rule::in(['active', 'trial', 'suspended', 'inactive'])],
            'theme_color' => ['nullable', 'string', 'max:20'],
        ]);

        $slug = Str::slug($validated['slug']);

        try {
            // 1. Create the tenant record (triggers DB creation, tenant migrations, and tenant seeders)
            $tenant = Tenant::create([
                'id' => $slug,
                'company_name' => $validated['company_name'],
                'slug' => $slug,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'vat_number' => $validated['vat_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'],
                'subscription_plan_id' => $validated['subscription_plan_id'] ?? null,
                'data' => [
                    'theme_color' => $validated['theme_color'] ?? '#32c5d2',
                    'fcm_topic' => $slug . '_fieldMA_allUser',
                    'feature_overrides' => [],
                ],
            ]);

            // 2. Attach Subdomain
            $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
            $subdomain = $slug . '.' . $centralDomain;
            $tenant->domains()->create(['domain' => $subdomain]);

            // 3. Attach Custom Domain (if provided)
            if (!empty($validated['custom_domain'])) {
                $customDomain = strtolower(trim($validated['custom_domain']));
                $tenant->domains()->create(['domain' => $customDomain]);
            }

            // 4. Initialize Tenant Admin inside tenant database
            try {
                $tenant->run(function () use ($validated) {
                    // Ensure Super-Admin role exists
                    $role = Role::firstOrCreate(
                        ['name' => 'Super-Admin', 'guard_name' => 'web']
                    );

                    $user = User::updateOrCreate(
                        ['email' => $validated['email']],
                        [
                            'name' => $validated['company_name'] . ' Admin',
                            'phone' => $validated['phone'] ?? '0000000000',
                            'password' => Hash::make($validated['password']),
                            'address' => $validated['address'] ?? '',
                            'user_type' => 'super',
                            'app' => 'SOA',
                            'status' => 'Active',
                        ]
                    );

                    $user->assignRole($role);
                });
            } catch (\Throwable $e) {
                \Log::warning("Tenant admin setup warning: " . $e->getMessage());
            }

            return redirect()->route('super-admin.tenants.show', $tenant)
                ->with('success', "Tenant workspace '{$tenant->company_name}' was created successfully.");
        } catch (\Throwable $e) {
            if (isset($tenant) && $tenant->exists) {
                try {
                    $tenant->delete();
                } catch (\Throwable $delEx) {
                    // Ignore rollback error
                }
            }
            return back()->withInput()->withErrors([
                'error' => 'Failed to provision tenant workspace: ' . $e->getMessage()
            ]);
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['domains', 'plan']);

        // Collect stats from tenant database
        $stats = [
            'users_count' => 0,
            'mobile_users_count' => 0,
            'dealers_count' => 0,
            'orders_count' => 0,
        ];

        try {
            $tenant->run(function () use (&$stats) {
                $stats['users_count'] = \App\Models\User::count();
                $stats['mobile_users_count'] = \App\Models\Mobileuser::count();
                $stats['dealers_count'] = \App\Models\Dealer::count();
                $stats['orders_count'] = \App\Models\Order::count();
            });
        } catch (\Throwable $e) {
            // DB might be pending migration or offline
        }

        return view('super-admin.tenants.show', compact('tenant', 'stats'));
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load(['domains', 'plan']);
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('super-admin.tenants.edit', compact('tenant', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'status' => ['required', Rule::in(['active', 'trial', 'suspended', 'inactive'])],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'custom_domain' => ['nullable', 'string', 'max:255'],
        ]);

        $data = $tenant->data ?? [];
        $data['theme_color'] = $validated['theme_color'] ?? ($data['theme_color'] ?? '#32c5d2');

        $tenant->update([
            'company_name' => $validated['company_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'vat_number' => $validated['vat_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
            'subscription_plan_id' => $validated['subscription_plan_id'] ?? null,
            'data' => $data,
        ]);

        // Manage custom domain
        if (!empty($validated['custom_domain'])) {
            $domainStr = strtolower(trim($validated['custom_domain']));
            $existing = $tenant->domains()->where('domain', $domainStr)->first();
            if (!$existing) {
                // Ensure not taken
                if (!\Stancl\Tenancy\Database\Models\Domain::where('domain', $domainStr)->where('tenant_id', '!=', $tenant->id)->exists()) {
                    $tenant->domains()->create(['domain' => $domainStr]);
                }
            }
        }

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', 'Tenant details updated successfully.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->update(['status' => $newStatus]);

        return back()->with('success', "Tenant status updated to {$newStatus}.");
    }

    public function migrate(Tenant $tenant)
    {
        try {
            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);

            return back()->with('success', "Migrations executed successfully for tenant {$tenant->company_name}.");
        } catch (\Throwable $e) {
            return back()->with('error', "Migration failed: " . $e->getMessage());
        }
    }

    public function destroy(Request $request, Tenant $tenant)
    {
        $dropDatabase = $request->boolean('drop_database', false);

        if (!$dropDatabase) {
            // Temporarily disable DB deletion hook
            $tenant->delete();
        } else {
            $tenant->delete();
        }

        return redirect()->route('super-admin.tenants.index')
            ->with('success', "Tenant {$tenant->company_name} deleted successfully.");
    }
}
