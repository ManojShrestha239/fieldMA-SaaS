@extends('super-admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--admin-text-title);">Subscription Tiers</h3>
            <p class="text-muted small mb-0">Define subscription packaging, user quotas, and enable/disable features per tier.</p>
        </div>
        <div>
            <a href="{{ route('super-admin.plans.create') }}" class="btn btn-admin-primary">
                <i class="fa-solid fa-plus"></i> Create New Plan
            </a>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($plans as $plan)
            <div class="col-md-6 col-lg-4">
                <div class="admin-card admin-card-hover h-100 d-flex flex-column p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="fw-bold mb-0" style="color: var(--admin-text-title);">{{ $plan->name }}</h4>
                        <span class="badge {{ $plan->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1 text-uppercase" style="font-size: 11px;">
                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h3 class="fw-bold d-inline" style="color: var(--admin-primary);">Rs. {{ $plan->price }}</h3>
                        <span class="text-muted small">/ {{ $plan->billing_interval }}</span>
                    </div>

                    <p class="text-muted small mb-3 flex-grow-0">{{ $plan->description ?? 'No description provided.' }}</p>

                    <div class="mb-3 py-2 border-top border-bottom border-slate-200 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Max Mobile Users:</span>
                            <strong style="color: var(--admin-text-title);">{{ $plan->max_mobile_users > 0 ? $plan->max_mobile_users : 'Unlimited' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Max Web Admins:</span>
                            <strong style="color: var(--admin-text-title);">{{ $plan->max_admin_users > 0 ? $plan->max_admin_users : 'Unlimited' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Assigned Companies:</span>
                            <span class="badge" style="background-color: var(--admin-canvas-dark); color: var(--admin-sidebar-bg);">{{ $plan->tenants_count }} tenants</span>
                        </div>
                    </div>

                    <div class="mb-4 flex-grow-1">
                        <span class="text-muted small fw-semibold d-block mb-2 text-uppercase tracking-wider" style="font-size: 11px;">Included Modules:</span>
                        @if ($plan->features && count($plan->features) > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($plan->features as $featureKey => $enabled)
                                    @if ($enabled)
                                        <span class="badge bg-light text-dark border small" style="font-size: 11px;">
                                            <i class="fa-solid fa-check text-success fa-xs me-1"></i>{{ ucwords(str_replace('_', ' ', $featureKey)) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted small fst-italic">All standard modules included</span>
                        @endif
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top border-slate-200 mt-auto">
                        <a href="{{ route('super-admin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
                            <i class="fa-solid fa-pen"></i> Edit Plan
                        </a>
                        @if ($plan->tenants_count === 0)
                            <form action="{{ route('super-admin.plans.destroy', $plan) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this subscription plan?');"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="admin-card p-5 text-center text-muted">
                    <i class="fa-solid fa-cube fa-3x mb-3 text-secondary"></i>
                    <h5 class="fw-bold mb-1">No Subscription Plans Configured</h5>
                    <p class="text-muted small mb-3">Create tiers to organize tenant resource quotas and billing plans.</p>
                    <a href="{{ route('super-admin.plans.create') }}" class="btn btn-admin-primary">
                        <i class="fa-solid fa-plus"></i> Create First Plan
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection
