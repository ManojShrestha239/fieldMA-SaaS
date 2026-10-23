@extends('super-admin.layouts.app')

@section('title', $tenant->company_name)

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Companies
            </a>
            <h3 class="fw-bold mb-1">{{ $tenant->company_name }}</h3>
            <p class="text-muted small mb-0">Tenant ID: <code>{{ $tenant->id }}</code> | Slug:
                <code>{{ $tenant->slug }}</code></p>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('super-admin.tenants.migrate', $tenant) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-sync me-1"></i> Run Migrations
                </button>
            </form>
            <form action="{{ route('super-admin.tenants.toggle-status', $tenant) }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn btn-sm {{ $tenant->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}">
                    <i class="fa-solid fa-power-off me-1"></i>
                    {{ $tenant->status === 'active' ? 'Suspend Workspace' : 'Activate Workspace' }}
                </button>
            </form>
            <a href="{{ route('super-admin.tenants.edit', $tenant) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-pen me-1"></i> Edit Workspace
            </a>
        </div>
    </div>

    <!-- Stats from Tenant DB -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <span class="text-muted small fw-semibold text-uppercase">Web Admin Users</span>
                <h3 class="fw-bold my-1">{{ $stats['users_count'] }}</h3>
                <span class="text-muted small">Portal accounts</span>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <span class="text-muted small fw-semibold text-uppercase">Mobile Field Users</span>
                <h3 class="fw-bold my-1 text-primary">{{ $stats['mobile_users_count'] }}</h3>
                <span class="text-muted small">
                    Quota:
                    {{ $tenant->plan && $tenant->plan->max_mobile_users > 0 ? $tenant->plan->max_mobile_users . ' max' : 'Unlimited' }}
                </span>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <span class="text-muted small fw-semibold text-uppercase">Dealers Enrolled</span>
                <h3 class="fw-bold my-1 text-info">{{ $stats['dealers_count'] }}</h3>
                <span class="text-muted small">Client dealers</span>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-stat bg-white p-3">
                <span class="text-muted small fw-semibold text-uppercase">Orders Placed</span>
                <h3 class="fw-bold my-1 text-success">{{ $stats['orders_count'] }}</h3>
                <span class="text-muted small">Total historical</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Workspace Overview -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Workspace Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 160px;">Status:</td>
                            <td>
                                <span
                                    class="badge badge-{{ $tenant->status }} px-2 py-1 text-uppercase">{{ $tenant->status }}</span>
                                @if ($tenant->isTrial())
                                    @if ($tenant->isTrialExpired())
                                        <span class="badge bg-danger ms-1">Trial Expired</span>
                                    @elseif($tenant->trial_ends_at)
                                        <span class="badge bg-info text-dark ms-1">Trial ends in
                                            {{ now()->diffInDays($tenant->trial_ends_at, false) }} days</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trial Ends At:</td>
                            <td>
                                @if ($tenant->trial_ends_at)
                                    {{ $tenant->trial_ends_at->format('M d, Y') }}
                                    <small class="text-muted">({{ $tenant->trial_ends_at->diffForHumans() }})</small>
                                @else
                                    <span class="text-muted">Not applicable</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Plan Ends At:</td>
                            <td>
                                @if ($tenant->plan_ends_at)
                                    {{ $tenant->plan_ends_at->format('M d, Y') }}
                                    <small class="text-muted">({{ $tenant->plan_ends_at->diffForHumans() }})</small>
                                    @if ($tenant->isPlanExpired())
                                        <span class="badge bg-danger ms-1">Expired</span>
                                    @endif
                                @else
                                    <span class="text-muted">No expiration set</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Contact Email:</td>
                            <td>{{ $tenant->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Phone:</td>
                            <td>{{ $tenant->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">VAT / PAN:</td>
                            <td>{{ $tenant->vat_number ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Address:</td>
                            <td>{{ $tenant->address ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Database Name:</td>
                            <td><code>{{ config('tenancy.database.prefix') . $tenant->id . config('tenancy.database.suffix') }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">FCM Topic:</td>
                            <td><code>{{ $tenant->getFcmTopic() }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Theme Color:</td>
                            <td>
                                <span class="d-inline-block rounded-circle me-1"
                                    style="width: 14px; height: 14px; background-color: {{ $tenant->getThemeColor() }}; vertical-align: middle;"></span>
                                <code>{{ $tenant->getThemeColor() }}</code>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Domains & Plan -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Assigned Domains</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-0">
                        @forelse ($tenant->domains as $domain)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <i class="fa-solid fa-globe text-primary me-2"></i>
                                    <strong>{{ $domain->domain }}</strong>
                                </div>
                                <a href="http://{{ $domain->domain }}:8000" target="_blank"
                                    class="btn btn-sm btn-outline-secondary">
                                    Open Portal <i class="fa-solid fa-external-link-alt fa-xs ms-1"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted px-0">No domains assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Subscription Plan</h5>
                </div>
                <div class="card-body">
                    @if ($tenant->plan)
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="fw-bold mb-0">{{ $tenant->plan->name }}</h5>
                                <small class="text-muted">Rs.
                                    {{ $tenant->plan->price }}/{{ $tenant->plan->billing_interval }}</small>
                            </div>
                            <span class="badge bg-success">Subscribed</span>
                        </div>
                        <p class="small text-muted mb-3">{{ $tenant->plan->description }}</p>
                        <div class="small">
                            <strong>Active Plan Modules:</strong>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                @if (!empty($tenant->plan->features))
                                    @foreach ($tenant->plan->features as $key => $enabled)
                                        @if ($enabled)
                                            <span class="badge bg-light text-dark border"><i
                                                    class="fa fa-check text-success me-1"></i>
                                                {{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-muted small mb-0">No subscription plan assigned. Company has custom access.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-danger mt-4">
        <div class="card-header bg-danger-subtle text-danger py-3">
            <h6 class="fw-bold mb-0"><i class="fa fa-triangle-exclamation me-1"></i> Danger Zone</h6>
        </div>
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">Delete Tenant Workspace</h6>
                <p class="text-muted small mb-0">Deleting this tenant will remove their platform registration and domain
                    routing.</p>
            </div>
            <form action="{{ route('super-admin.tenants.destroy', $tenant) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to permanently delete this workspace?');">
                @csrf
                @method('DELETE')
                <div class="form-check form-check-inline me-2">
                    <input class="form-check-input" type="checkbox" name="drop_database" value="1"
                        id="dropDbCheck">
                    <label class="form-check-label small text-danger fw-semibold" for="dropDbCheck">Drop MySQL
                        Database</label>
                </div>
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fa fa-trash me-1"></i> Delete Workspace
                </button>
            </form>
        </div>
    </div>
@endsection
