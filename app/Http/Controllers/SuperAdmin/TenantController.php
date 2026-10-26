<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            'trial_ends_at' => ['nullable', 'date'],
            'plan_ends_at' => ['nullable', 'date'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'mimes:jpeg,png,jpg,svg,webp,ico', 'max:1024'],
        ]);

        $slug = Str::slug($validated['slug']);
        $status = $validated['status'];

        // Calculate trial_ends_at
        $trialEndsAt = !empty($validated['trial_ends_at']) ? Carbon::parse($validated['trial_ends_at']) : null;
        if ($status === 'trial' && !$trialEndsAt) {
            $trialEndsAt = now()->addDays(14);
        }

        // Calculate plan_ends_at
        $planEndsAt = !empty($validated['plan_ends_at']) ? Carbon::parse($validated['plan_ends_at']) : null;
        if (!empty($validated['subscription_plan_id']) && !$planEndsAt) {
            $plan = SubscriptionPlan::find($validated['subscription_plan_id']);
            if ($plan) {
                if ($plan->billing_interval === 'yearly') {
                    $planEndsAt = now()->addYear();
                } elseif ($plan->billing_interval === 'monthly') {
                    $planEndsAt = now()->addMonth();
                }
            }
        }

        try {
            // 1. Create the tenant record
            $tenant = Tenant::create([
                'id' => $slug,
                'company_name' => $validated['company_name'],
                'slug' => $slug,
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'vat_number' => $validated['vat_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $status,
                'subscription_plan_id' => $validated['subscription_plan_id'] ?? null,
                'trial_ends_at' => $trialEndsAt,
                'plan_ends_at' => $planEndsAt,
            ]);

            // Save virtual columns directly on model so VirtualColumn trait packs them
            $tenant->theme_color = $validated['theme_color'] ?? '#C0392B';
            $tenant->fcm_topic = $slug . '_fieldMA_allUser';
            $tenant->feature_overrides = [];

            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('branding/logos', 'public');
                $tenant->logo_url = '/storage/' . $logoPath;
            }

            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('branding/favicons', 'public');
                $tenant->favicon_url = '/storage/' . $faviconPath;
            }

            $tenant->save();

            // Ensure database exists and is migrated (fallback safety check)
            if (!$tenant->database()->manager()->databaseExists($tenant->database()->getName())) {
                $tenant->database()->manager()->createDatabase($tenant);
                Artisan::call('tenants:migrate', [
                    '--tenants' => [$tenant->id],
                    '--force' => true,
                ]);
                Artisan::call('tenants:seed', [
                    '--tenants' => [$tenant->id],
                    '--force' => true,
                ]);
            }

            // 2. Attach Primary Subdomain (created from slug)
            $centralDomain = config('tenancy.central_domains')[0] ?? 'localhost';
            $subdomain = $slug . '.' . $centralDomain;
            $tenant->domains()->create(['domain' => $subdomain]);

            // 3. Attach Custom Domain (only if explicitly provided in request)
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

                    // // Remove default test@test.com
                    // User::where('email', 'test@test.com')->delete();

                    $user = User::updateOrCreate(
                        ['email' => $validated['email']],
                        [
                            'name' => $validated['company_name'] . ' Admin',
                            'phone' => $validated['phone'] ?? '0000000000',
                            'password' => Hash::make($validated['password']),
                            'user_type' => 'super',
                            'app' => 'SOA',
                            'status' => 'Active',
                        ]
                    );

                    $user->assignRole($role);
                });
            } catch (\Throwable $e) {
                Log::warning("Tenant admin setup warning: " . $e->getMessage());
            }

            return redirect()->route('super-admin.tenants.show', $tenant)
                ->with('success', "Tenant workspace '{$tenant->company_name}' was created successfully.");
        } catch (\Throwable $e) {
            if (isset($tenant) && $tenant->exists) {
                try {
                    $tenant->delete();
                } catch (\Throwable $deleteErr) {
                    // cleanup
                }
            }
            return back()->withInput()->withErrors(['error' => 'Failed to provision tenant workspace: ' . $e->getMessage()]);
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
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
            'status' => ['required', Rule::in(['active', 'trial', 'suspended', 'inactive'])],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'custom_domain' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('domains', 'domain')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', '!=', $tenant->id);
                }),
            ],
            'trial_ends_at' => ['nullable', 'date'],
            'plan_ends_at' => ['nullable', 'date'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'mimes:jpeg,png,jpg,svg,webp,ico', 'max:1024'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],
        ]);

        $oldEmail = $tenant->email;
        $newEmail = $validated['email'];
        $newPassword = $validated['password'] ?? null;

        // Calculate trial_ends_at
        $trialEndsAt = !empty($validated['trial_ends_at']) ? Carbon::parse($validated['trial_ends_at']) : null;
        if ($validated['status'] === 'trial' && !$trialEndsAt && !$tenant->trial_ends_at) {
            $trialEndsAt = now()->addDays(14);
        }

        // Calculate plan_ends_at
        $planEndsAt = !empty($validated['plan_ends_at']) ? Carbon::parse($validated['plan_ends_at']) : null;
        if (!empty($validated['subscription_plan_id']) && !$planEndsAt && !$tenant->plan_ends_at) {
            $plan = SubscriptionPlan::find($validated['subscription_plan_id']);
            if ($plan) {
                if ($plan->billing_interval === 'yearly') {
                    $planEndsAt = now()->addYear();
                } elseif ($plan->billing_interval === 'monthly') {
                    $planEndsAt = now()->addMonth();
                }
            }
        }

        $tenant->update([
            'company_name' => $validated['company_name'],
            'email' => $newEmail,
            'phone' => $validated['phone'] ?? null,
            'vat_number' => $validated['vat_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'],
            'subscription_plan_id' => $validated['subscription_plan_id'] ?? null,
            'trial_ends_at' => $trialEndsAt,
            'plan_ends_at' => $planEndsAt,
        ]);

        // Update virtual columns directly on model
        if (!empty($validated['theme_color'])) {
            $tenant->theme_color = $validated['theme_color'];
        }

        // Handle logo upload / removal
        if ($request->boolean('remove_logo')) {
            if ($tenant->logo_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $tenant->logo_url));
            }
            $tenant->logo_url = null;
        } elseif ($request->hasFile('logo')) {
            if ($tenant->logo_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $tenant->logo_url));
            }
            $logoPath = $request->file('logo')->store('branding/logos', 'public');
            $tenant->logo_url = '/storage/' . $logoPath;
        }

        // Handle favicon upload / removal
        if ($request->boolean('remove_favicon')) {
            if ($tenant->favicon_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $tenant->favicon_url));
            }
            $tenant->favicon_url = null;
        } elseif ($request->hasFile('favicon')) {
            if ($tenant->favicon_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $tenant->favicon_url));
            }
            $faviconPath = $request->file('favicon')->store('branding/favicons', 'public');
            $tenant->favicon_url = '/storage/' . $faviconPath;
        }

        $tenant->save();

        // Sync admin credentials inside the tenant's database
        try {
            $tenant->run(function () use ($oldEmail, $newEmail, $newPassword, $validated) {
                $user = User::where('email', $oldEmail)->first()
                    ?? User::where('email', $newEmail)->first()
                    ?? User::where('user_type', 'super')->first();

                if ($user) {
                    $user->email = $newEmail;
                    $user->name = $validated['company_name'] . ' Admin';
                    if (!empty($validated['phone'])) {
                        $user->phone = $validated['phone'];
                    }
                    if (!empty($newPassword)) {
                        $user->password = Hash::make($newPassword);
                    }
                    $user->save();
                }
            });
        } catch (\Throwable $e) {
            Log::warning("Tenant admin update warning: " . $e->getMessage());
        }

        // Manage custom domain (optional)
        $currentCustomDomain = $tenant->getCustomDomain();
        $newCustomDomain = !empty($validated['custom_domain']) ? strtolower(trim($validated['custom_domain'])) : null;

        if ($newCustomDomain) {
            if ($currentCustomDomain) {
                if ($currentCustomDomain !== $newCustomDomain) {
                    $tenant->domains()->where('domain', $currentCustomDomain)->update(['domain' => $newCustomDomain]);
                }
            } else {
                $tenant->domains()->create(['domain' => $newCustomDomain]);
            }
        } elseif ($currentCustomDomain) {
            // User cleared the optional custom domain, remove it
            $tenant->domains()->where('domain', $currentCustomDomain)->delete();
        }

        // Clean up any legacy bare slug domain record
        $tenant->domains()->where('domain', $tenant->slug)->delete();

        return redirect()->route('super-admin.tenants.show', $tenant)
            ->with('success', 'Tenant details and credentials updated successfully.');
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
            if (!$tenant->database()->manager()->databaseExists($tenant->database()->getName())) {
                $tenant->database()->manager()->createDatabase($tenant);
            }

            Artisan::call('tenants:migrate', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);

            return back()->with('success', "Migrations executed successfully for tenant {$tenant->company_name}.");
        } catch (\Throwable $e) {
            return back()->with('error', "Migration failed: " . $e->getMessage());
        }
    }

    public function rollback(Tenant $tenant)
    {
        try {
            Artisan::call('tenants:rollback', [
                '--tenants' => [$tenant->id],
                '--force' => true,
            ]);

            return back()->with('success', "Migration rollback executed successfully for tenant {$tenant->company_name}.");
        } catch (\Throwable $e) {
            return back()->with('error', "Migration rollback failed: " . $e->getMessage());
        }
    }

    public function storageLink(Tenant $tenant)
    {
        try {
            Artisan::call('storage:link');

            return back()->with('success', "Storage symbolic link created successfully.");
        } catch (\Throwable $e) {
            return back()->with('error', "Storage link failed: " . $e->getMessage());
        }
    }

    public function optimizeClear(Tenant $tenant)
    {
        try {
            Artisan::call('optimize:clear');
            Artisan::call('config:cache');

            return back()->with('success', "Application cache cleared and configuration cached successfully.");
        } catch (\Throwable $e) {
            return back()->with('error', "Failed to optimize and cache config: " . $e->getMessage());
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
