<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    // public const AVAILABLE_FEATURES = [
    //     'attendance' => 'Attendance & Daily Check-in',
    //     'order_booking' => 'Order Booking & History',
    //     'dealers_management' => 'Dealer & Sub-dealer Management',
    //     'notices' => 'Notice Board & Alerts',
    //     'payments' => 'Payment & Collection Tracking',
    //     'tada' => 'TADA / Travel & Expense Reimbursement',
    //     'site_registration' => 'Site Registration & Construction Stages',
    //     'stock_management' => 'Dealer Stock & Inventory Transactions',
    //     'raw_inventory' => 'Raw Materials & Product Manufacturing',
    //     'live_tracking' => 'Live Location Tracking',
    // ];

    // public const AVAILABLE_FEATURES = config('features.map', []);

    public function index()
    {
        $plans = SubscriptionPlan::withCount('tenants')->get();

        return view('super-admin.plans.index', [
            'plans' => $plans,
            'availableFeatures' => config('features.map', []),
        ]);
    }

    public function create()
    {
        return view('super-admin.plans.create', [
            'availableFeatures' => config('features.map', []),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:subscription_plans,slug'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_interval' => ['required', Rule::in(['monthly', 'yearly', 'lifetime'])],
            'max_mobile_users' => ['required', 'integer', 'min:0'],
            'max_admin_users' => ['required', 'integer', 'min:0'],
            'features' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $features = $this->buildFeaturesPayload($request);

        $plan = SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'billing_interval' => $validated['billing_interval'],
            'max_mobile_users' => $validated['max_mobile_users'],
            'max_admin_users' => $validated['max_admin_users'],
            'features' => $features,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('super-admin.plans.index')
            ->with('success', "Subscription Plan '{$plan->name}' created successfully.");
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('super-admin.plans.edit', [
            'plan' => $plan,
            'availableFeatures' => config('features.map', []),
        ]);
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', Rule::unique('subscription_plans')->ignore($plan->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['required', 'numeric', 'min:0'],
            'billing_interval' => ['required', Rule::in(['monthly', 'yearly', 'lifetime'])],
            'max_mobile_users' => ['required', 'integer', 'min:0'],
            'max_admin_users' => ['required', 'integer', 'min:0'],
            'features' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ]);

        $features = $this->buildFeaturesPayload($request);

        $plan->update([
            'name' => $validated['name'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'billing_interval' => $validated['billing_interval'],
            'max_mobile_users' => $validated['max_mobile_users'],
            'max_admin_users' => $validated['max_admin_users'],
            'features' => $features,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('super-admin.plans.index')
            ->with('success', "Plan '{$plan->name}' updated successfully.");
    }

    /**
     * Build the filtered features JSON array storing only enabled modules and enabled sub-features.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    protected function buildFeaturesPayload(Request $request): array
    {
        $features = [];
        $available = config('features.map', []);

        foreach ($available as $fKey => $fConfig) {
            $submittedSubs = (array) $request->input("features.{$fKey}.sub_features", []);
            $enabledSubs = [];

            if (!empty($fConfig['sub_features']) && is_array($fConfig['sub_features'])) {
                foreach ($fConfig['sub_features'] as $subKey) {
                    if (!empty($submittedSubs[$subKey]) || in_array($subKey, $submittedSubs, true)) {
                        $enabledSubs[] = $subKey;
                    }
                }
            }

            // Auto-select main feature on backend if any sub-feature is selected, or if master is checked
            $hasAnySubSelected = count($enabledSubs) > 0;
            $masterChecked = !empty($request->input("features.{$fKey}.enabled"))
                || $request->input("features.{$fKey}") === '1'
                || $request->input("features.{$fKey}") === 1;

            $isModuleEnabled = $masterChecked || $hasAnySubSelected;

            // If main feature is not enabled, auto-deselect (omit module and all sub-features completely)
            if (!$isModuleEnabled) {
                continue;
            }

            $featureEntry = [];
            if (!empty($fConfig['sub_features']) && is_array($fConfig['sub_features'])) {
                $featureEntry['sub_features'] = array_values($enabledSubs);
            }

            // (object) [] ensures it serializes to "{}" in JSON when empty
            $features[$fKey] = !empty($featureEntry) ? $featureEntry : (object) [];
        }

        return $features;
    }

    public function destroy(SubscriptionPlan $plan)
    {
        if ($plan->tenants()->exists()) {
            return back()->with('error', 'Cannot delete this plan because it is currently assigned to one or more tenants.');
        }

        $plan->delete();

        return redirect()->route('super-admin.plans.index')
            ->with('success', "Plan '{$plan->name}' deleted successfully.");
    }
}
