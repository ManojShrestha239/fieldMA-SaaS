@extends('super-admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--admin-text-title);">Platform Dashboard</h3>
            <p class="text-muted small mb-0">Overview of tenants, subscriptions, and platform activity.</p>
        </div>
        <div>
            <a href="{{ route('super-admin.tenants.create') }}" class="btn btn-admin-primary">
                <i class="fa-solid fa-plus"></i> Provision New Company
            </a>
        </div>
    </div>

    <!-- 10% Accent & KPI Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Tenants -->
        <div class="col-sm-6 col-lg-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Total Tenants</span>
                        <h3 class="fw-bold my-1" style="color: var(--admin-text-title);">{{ $totalTenants }}</h3>
                        <span class="text-muted small">Registered organizations</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #EEF2FF; color: var(--admin-sidebar-bg);">
                        <i class="fa-solid fa-building fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Active Workspaces -->
        <div class="col-sm-6 col-lg-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Active Workspaces</span>
                        <h3 class="fw-bold my-1 text-success">{{ $activeTenants }}</h3>
                        <span class="text-muted small">Live & operational</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: var(--admin-status-active-bg); color: var(--admin-status-active-text);">
                        <i class="fa-solid fa-circle-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Suspended / At Risk -->
        <div class="col-sm-6 col-lg-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Suspended</span>
                        <h3 class="fw-bold my-1" style="color: var(--admin-primary);">{{ $suspendedTenants }}</h3>
                        <span class="text-muted small">Access locked / halted</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: var(--admin-primary-subtle); color: var(--admin-primary);">
                        <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Subscription Plans -->
        <div class="col-sm-6 col-lg-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase tracking-wider">Subscription Tiers</span>
                        <h3 class="fw-bold my-1" style="color: var(--admin-accent-hover);">{{ $totalPlans }}</h3>
                        <span class="text-muted small">Configured packages</span>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: var(--admin-accent-subtle); color: var(--admin-accent);">
                        <i class="fa-solid fa-cubes fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 30% Structural: Recent Companies <TableCard> -->
    <div class="admin-table-card">
        <div class="d-flex justify-content-between align-items-center px-4 py-3 bg-white border-bottom border-slate-200">
            <div class="d-flex align-items-center gap-2">
                <span class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; color: var(--admin-sidebar-bg);">
                    <i class="fa-solid fa-building-user fa-sm"></i>
                </span>
                <h5 class="fw-bold mb-0" style="color: var(--admin-text-title); font-size: 16px;">Recent Companies</h5>
            </div>
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-sm btn-outline-secondary">
                View All Companies <i class="fa-solid fa-arrow-right fa-xs ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Subdomain & Domain</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentTenants as $tenant)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $tenant->getLogoUrl() }}" alt="{{ $tenant->company_name }}" class="rounded border p-1 bg-white flex-shrink-0" style="width: 38px; height: 38px; object-fit: contain;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $tenant->company_name }}</div>
                                        <small class="text-muted">{{ $tenant->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach ($tenant->domains as $d)
                                    <a href="http://{{ $d->domain }}" target="_blank"
                                        class="badge bg-light text-dark text-decoration-none border me-1">
                                        {{ $d->domain }} <i class="fa-solid fa-external-link-alt fa-xs text-muted"></i>
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $tenant->plan->name ?? 'None' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $tenant->status }} px-2 py-1 text-uppercase" style="font-size: 11px;">
                                    {{ $tenant->status }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $tenant->created_at->format('M d, Y') }}</small></td>
                            <td class="text-end">
                                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-eye text-primary"></i> Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-building fa-2x mb-2 d-block text-secondary"></i>
                                No tenant companies have been provisioned yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
