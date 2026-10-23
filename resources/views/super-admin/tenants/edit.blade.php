@extends('super-admin.layouts.app')

@section('title', 'Edit ' . $tenant->company_name)

@section('content')
<div class="mb-4">
    <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Tenant
    </a>
    <h3 class="fw-bold mb-1">Edit Tenant Workspace</h3>
    <p class="text-muted small mb-0">Modify company metadata, subscription tier, custom domains, and status.</p>
</div>

<div class="card border-0 shadow-sm col-lg-8">
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('super-admin.tenants.update', $tenant) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold text-primary mb-3">Company Details</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $tenant->company_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Primary Contact / Admin Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $tenant->email) }}" required>
                    <small class="text-muted">Updates tenant record and syncs with tenant admin user login.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Reset Admin Password (Optional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave empty to keep existing password">
                    <small class="text-muted">Enter at least 8 characters to reset the tenant admin's password.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Brand Primary Color</label>
                    <input type="color" name="theme_color" class="form-control form-control-color w-100" value="{{ old('theme_color', $tenant->getThemeColor()) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">VAT / PAN Number</label>
                    <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $tenant->vat_number) }}">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $tenant->address) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Add Custom Domain</label>
                    <input type="text" name="custom_domain" class="form-control" placeholder="e.g. portal.acmecorp.com">
                </div>
            </div>

            <h5 class="fw-bold text-primary mb-3">Subscription & Access Status</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Subscription Plan</label>
                    <select name="subscription_plan_id" class="form-select">
                        <option value="">No Plan Assigned</option>
                        @foreach ($plans as $p)
                            <option value="{{ $p->id }}" {{ old('subscription_plan_id', $tenant->subscription_plan_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} (${{ $p->price }}/{{ $p->billing_interval }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Workspace Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', $tenant->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="trial" {{ old('status', $tenant->status) === 'trial' ? 'selected' : '' }}>Trial</option>
                        <option value="suspended" {{ old('status', $tenant->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="inactive" {{ old('status', $tenant->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Trial Expiration Date</label>
                    <input type="date" name="trial_ends_at" class="form-control" value="{{ old('trial_ends_at', optional($tenant->trial_ends_at)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Plan Expiration Date</label>
                    <input type="date" name="plan_ends_at" class="form-control" value="{{ old('plan_ends_at', optional($tenant->plan_ends_at)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('super-admin.tenants.show', $tenant) }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
