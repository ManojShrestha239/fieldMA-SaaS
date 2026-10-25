@extends('super-admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Platform Dashboard</h3>
            <p class="text-muted small mb-0">Overview of tenants, subscriptions, and platform activity.</p>
        </div>
        <a href="{{ route('super-admin.tenants.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Provision New Company
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Total Tenants</span>
                        <h3 class="fw-bold my-1">{{ $totalTenants }}</h3>
                        <span class="text-muted small">Registered companies</span>
                    </div>
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle">
                        <i class="fa-solid fa-building fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Active Workspaces</span>
                        <h3 class="fw-bold my-1 text-success">{{ $activeTenants }}</h3>
                        <span class="text-muted small">Live & operational</span>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-circle">
                        <i class="fa-solid fa-circle-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Suspended</span>
                        <h3 class="fw-bold my-1 text-danger">{{ $suspendedTenants }}</h3>
                        <span class="text-muted small">Access locked</span>
                    </div>
                    <div class="bg-danger-subtle text-danger p-3 rounded-circle">
                        <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold text-uppercase">Subscription Plans</span>
                        <h3 class="fw-bold my-1 text-info">{{ $totalPlans }}</h3>
                        <span class="text-muted small">Tier packages</span>
                    </div>
                    <div class="bg-info-subtle text-info p-3 rounded-circle">
                        <i class="fa-solid fa-cube fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Companies Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Recent Companies</h5>
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Company</th>
                        <th>Subdomain / Domain</th>
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
                                <div class="fw-bold">{{ $tenant->company_name }}</div>
                                <small class="text-muted">{{ $tenant->email }}</small>
                            </td>
                            <td>
                                @foreach ($tenant->domains as $d)
                                    <a href="http://{{ $d->domain }}" target="_blank"
                                        class="badge bg-light text-dark text-decoration-none border me-1">
                                        {{ $d->domain }} <i class="fa-solid fa-external-link-alt fa-xs"></i>
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $tenant->plan->name ?? 'None' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $tenant->status }} px-2 py-1 text-uppercase"
                                    style="font-size: 11px;">
                                    {{ $tenant->status }}
                                </span>
                            </td>
                            <td><small class="text-muted">{{ $tenant->created_at->format('M d, Y') }}</small></td>
                            <td class="text-end">
                                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-sm btn-light">
                                    <i class="fa-solid fa-eye"></i> Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-building fa-2x mb-2 d-block"></i>
                                No tenant companies have been created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
