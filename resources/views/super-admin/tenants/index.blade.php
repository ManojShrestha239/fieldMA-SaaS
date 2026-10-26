@extends('super-admin.layouts.app')

@section('title', 'Companies & Tenants')

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--admin-text-title);">Company Tenants</h3>
        <p class="text-muted small mb-0">Manage all registered organizations, white-label domains, and multi-tenant instances.</p>
    </div>
    <div>
        <a href="{{ route('super-admin.tenants.create') }}" class="btn btn-admin-primary">
            <i class="fa-solid fa-plus"></i> Provision New Company
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="admin-card mb-4 p-3 bg-white">
    <form action="{{ route('super-admin.tenants.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search company name, slug, or email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="plan_id" class="form-select">
                <option value="">All Subscription Tiers</option>
                @foreach ($plans as $p)
                    <option value="{{ $p->id }}" {{ request('plan_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-admin-outline w-100 justify-content-center">
                <i class="fa fa-filter fa-xs"></i> Filter
            </button>
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-admin-outline" title="Reset Filters">
                <i class="fa fa-rotate-left"></i>
            </a>
        </div>
    </form>
</div>

<!-- 30% Structural: Tenants <TableCard> -->
<div class="admin-table-card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Company Workspace</th>
                    <th>Subdomain & Domains</th>
                    <th>Plan Tier</th>
                    <th>Status</th>
                    <th>Provisioned</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tenants as $tenant)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $tenant->getLogoUrl() }}" alt="{{ $tenant->company_name }}" class="rounded border p-1 bg-white flex-shrink-0" style="width: 40px; height: 40px; object-fit: contain;">
                                <div>
                                    <div class="fw-bold" style="color: var(--admin-text-title);">{{ $tenant->company_name }}</div>
                                    <small class="text-muted">{{ $tenant->email }}</small>
                                    @if($tenant->phone)
                                        <small class="text-muted d-block" style="font-size: 11px;"><i class="fa fa-phone fa-xs me-1"></i>{{ $tenant->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @foreach ($tenant->domains as $d)
                                @if ($d->domain !== $tenant->slug)
                                <div class="mb-1">
                                    <a href="http://{{ $d->domain }}" target="_blank" class="badge bg-light text-dark text-decoration-none border">
                                        <i class="fa-solid fa-globe fa-xs text-primary me-1"></i> {{ $d->domain }}
                                    </a>
                                </div>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $tenant->plan->name ?? 'Custom / None' }}</span>
                            @if($tenant->plan)
                                <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                    {{ $tenant->plan->max_mobile_users > 0 ? $tenant->plan->max_mobile_users . ' users' : 'Unlimited users' }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $tenant->status }} px-2 py-1 text-uppercase" style="font-size: 11px;">
                                {{ $tenant->status }}
                            </span>
                        </td>
                        <td>
                            <span class="small" style="color: var(--admin-text-body);">{{ $tenant->created_at->format('M d, Y') }}</span>
                            <small class="text-muted d-block" style="font-size: 11px;">{{ $tenant->created_at->diffForHumans() }}</small>
                        </td>
                        <td class="text-end">
                            <div class="btn-group shadow-sm" role="group">
                                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-sm btn-outline-secondary" title="Manage Workspace">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                                <a href="{{ route('super-admin.tenants.edit', $tenant) }}" class="btn btn-sm btn-outline-secondary" title="Edit Company">
                                    <i class="fa-solid fa-pen text-secondary"></i>
                                </a>
                                <form action="{{ route('super-admin.tenants.toggle-status', $tenant) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="{{ $tenant->status === 'active' ? 'Suspend Workspace' : 'Activate Workspace' }}">
                                        <i class="fa-solid fa-power-off {{ $tenant->status === 'active' ? 'text-danger' : 'text-success' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-building-circle-xmark fa-3x mb-3 text-secondary"></i>
                            <p class="mb-0">No company workspaces found matching the query.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tenants->hasPages())
        <div class="p-3 bg-white border-top border-slate-200">
            {{ $tenants->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
