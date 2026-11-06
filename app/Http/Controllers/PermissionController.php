<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of roles and the role creation form.
     */
    public function index()
    {
        $tenant = tenant();
        $roles = Role::all();
        $tenantFeatures = $this->getTenantAllowedFeatures($tenant);
        $corePermissions = config('features.core_permissions', []);

        return view('dashboards.spatieroles.store', [
            'roles' => $roles,
            'tenantFeatures' => $tenantFeatures,
            'corePermissions' => $corePermissions,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Store a newly created role with selected module feature flags and permissions.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'role' => 'required|string|max:125|unique:spatie_roles,name',
            'features' => 'nullable|array',
            'core_permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => trim($request->role),
            'guard_name' => 'web',
        ]);

        $permissions = $this->resolvePermissionsFromRequest($request);
        $this->ensurePermissionsExist($permissions);
        $role->syncPermissions($permissions);

        Session::flash('Status', "Role '{$role->name}' added successfully.");

        return redirect()->route('role-permission.index');
    }

    /**
     * Show the form for editing an existing role.
     */
    public function edit($id)
    {
        $tenant = tenant();
        $role = Role::findOrFail($id);
        $roles = Role::all();
        $tenantFeatures = $this->getTenantAllowedFeatures($tenant);
        $corePermissions = config('features.core_permissions', []);
        $rolePermissionNames = $role->permissions->pluck('name')->toArray();

        return view('dashboards.spatieroles.edit', [
            'role' => $role,
            'roles' => $roles,
            'tenantFeatures' => $tenantFeatures,
            'corePermissions' => $corePermissions,
            'rolePermissionNames' => $rolePermissionNames,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Update an existing role with selected module feature flags and permissions.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:125|unique:spatie_roles,name,'.$id,
            'features' => 'nullable|array',
            'core_permissions' => 'nullable|array',
        ]);

        $role = Role::findOrFail($id);
        $role->name = trim($request->name);
        $role->save();

        $permissions = $this->resolvePermissionsFromRequest($request);
        $this->ensurePermissionsExist($permissions);
        $role->syncPermissions($permissions);

        Session::flash('Status', "Role '{$role->name}' updated successfully.");

        return redirect()->route('role-permission.index');
    }

    /**
     * Retrieve features allowed specifically for this tenant.
     * Enforces the tenant boundary rule: tenants can only see/do what their plan has.
     *
     * @param  mixed  $tenant
     * @return array<string, mixed>
     */
    protected function getTenantAllowedFeatures($tenant): array
    {
        $allFeatures = config('features.map', []);
        $allowed = [];

        foreach ($allFeatures as $fKey => $fConfig) {
            // STRICT TENANT PLAN CHECK:
            // If tenant exists and does not have this feature, OMIT IT ENTIRELY
            if ($tenant && method_exists($tenant, 'hasFeature') && ! $tenant->hasFeature($fKey)) {
                continue;
            }

            $entry = $fConfig;
            if (! empty($fConfig['sub_features'])) {
                $allowedSubs = [];
                foreach ($fConfig['sub_features'] as $subKey) {
                    if (! $tenant || ! method_exists($tenant, 'hasSubFeature') || $tenant->hasSubFeature($fKey, $subKey)) {
                        $allowedSubs[] = $subKey;
                    }
                }
                $entry['sub_features'] = $allowedSubs;
            }

            $allowed[$fKey] = $entry;
        }

        return $allowed;
    }

    /**
     * Parse requested features and core permissions into a flat list of permission names.
     *
     * @return array<string>
     */
    protected function resolvePermissionsFromRequest(Request $request): array
    {
        $tenant = tenant();
        $allowedFeatures = $this->getTenantAllowedFeatures($tenant);
        $permMap = config('features.permissions_map', []);
        $permissions = ['home', 'map']; // Base default permissions for web dashboard

        // 1. Process Core Administration Permissions
        $coreSelected = (array) $request->input('core_permissions', []);
        $coreAvailable = array_keys(config('features.core_permissions', []));
        foreach ($coreSelected as $perm) {
            if (in_array($perm, $coreAvailable, true)) {
                $permissions[] = $perm;
            }
        }

        // 2. Process Feature Modules & Sub-features (strictly bounded by allowed features)
        $featuresInput = (array) $request->input('features', []);
        foreach ($allowedFeatures as $fKey => $fConfig) {
            $fInput = $featuresInput[$fKey] ?? null;
            if (! $fInput) {
                continue;
            }

            $isMasterChecked = ! empty($fInput['enabled']);
            $submittedSubs = (array) ($fInput['sub_features'] ?? []);
            $enabledSubs = [];

            if (! empty($fConfig['sub_features'])) {
                foreach ($fConfig['sub_features'] as $subKey) {
                    if (! empty($submittedSubs[$subKey])) {
                        $enabledSubs[] = $subKey;
                    }
                }
            }

            if ($isMasterChecked || count($enabledSubs) > 0) {
                // Add parent permissions mapped to this feature (e.g. notices -> notice, dealer_orders -> order)
                if (! empty($permMap[$fKey])) {
                    foreach ($permMap[$fKey] as $p) {
                        $permissions[] = $p;
                    }
                }

                // Add canonical module permission name
                $permissions[] = $fKey;

                // Add fine-grained sub-feature permissions (e.g. dealer_orders.new_order)
                foreach ($enabledSubs as $subKey) {
                    $permissions[] = "{$fKey}.{$subKey}";
                }
            }
        }

        return array_values(array_unique($permissions));
    }

    /**
     * Ensure all given permission names exist in the database for the 'web' guard.
     *
     * @param  array<string>  $permissions
     */
    protected function ensurePermissionsExist(array $permissions): void
    {
        foreach ($permissions as $permName) {
            Permission::firstOrCreate(
                ['name' => $permName, 'guard_name' => 'web']
            );
        }
    }
}
