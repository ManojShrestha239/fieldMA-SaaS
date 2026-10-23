<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::whereIn('status', ['active', 'trial'])->count();
        $suspendedTenants = Tenant::where('status', 'suspended')->count();
        $totalPlans = SubscriptionPlan::count();

        $recentTenants = Tenant::with(['domains', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        return view('super-admin.dashboard', compact(
            'totalTenants',
            'activeTenants',
            'suspendedTenants',
            'totalPlans',
            'recentTenants'
        ));
    }
}
