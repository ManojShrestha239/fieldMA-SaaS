@extends('super-admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Subscription Plans</h3>
            <p class="text-muted small mb-0">Define subscription tiers, user quotas, and enable/disable system modules per
                plan.</p>
        </div>
        <a href="{{ route('super-admin.plans.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Create New Plan
        </a>
    </div>

    <div class="row g-4">
        @forelse ($plans as $plan)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h4 class="fw-bold mb-0 text-primary">{{ $plan->name }}</h4>
                            <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <h3 class="fw-bold d-inline">Rs. {{ $plan->price }}</h3>
                            <span class="text-muted small">/ {{ $plan->billing_interval }}</span>
                        </div>
                        <p class="text-muted small mb-3">{{ $plan->description ?? 'No description provided.' }}</p>

                        <div class="mb-3 py-2 border-top border-bottom small">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Max Mobile Users:</span>
                                <strong>{{ $plan->max_mobile_users > 0 ? $plan->max_mobile_users : 'Unlimited' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Max Web Admins:</span>
                                <strong>{{ $plan->max_admin_users > 0 ? $plan->max_admin_users : 'Unlimited' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Assigned Companies:</span>
                                <span class="badge bg-primary-subtle text-primary">{{ $plan->tenants_count }} tenants</span>
                            </div>
                        </div>

                        <div class="mb-4 flex-grow-1">
                            <span class="text-muted small fw-semibold d-block mb-2">Included Modules:</span>
                            <ul class="list-unstyled small mb-0">
                                @foreach ($availableFeatures as $fKey => $fLabel)
                                    <li
                                        class="mb-1 {{ $plan->hasFeature($fKey) ? 'text-dark' : 'text-muted text-decoration-line-through' }}">
                                        <i
                                            class="fa-solid {{ $plan->hasFeature($fKey) ? 'fa-check text-success' : 'fa-xmark text-secondary' }} me-2"></i>
                                        {{ $fLabel }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="d-flex gap-2 pt-2 border-top">
                            <a href="{{ route('super-admin.plans.edit', $plan) }}"
                                class="btn btn-outline-primary btn-sm flex-grow-1">
                                <i class="fa-solid fa-pen me-1"></i> Edit Plan
                            </a>
                            @if ($plan->tenants_count === 0)
                                <form action="{{ route('super-admin.plans.destroy', $plan) }}" method="POST"
                                    onsubmit="return confirm('Delete this plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <p>No subscription plans have been created yet.</p>
            </div>
        @endforelse
    </div>
@endsection
