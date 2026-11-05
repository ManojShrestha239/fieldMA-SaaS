<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Traits\HandlesFeaturePayload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    use HandlesFeaturePayload;
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
