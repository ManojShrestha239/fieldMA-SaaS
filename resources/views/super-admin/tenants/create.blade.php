@extends('super-admin.layouts.app')

@section('title', 'Provision New Company')

@section('content')
<div class="mb-4">
    <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Companies
    </a>
    <h3 class="fw-bold mb-1">Provision New Company Workspace</h3>
    <p class="text-muted small mb-0">Create a new tenant, configure its dedicated database, and seed initial administrator credentials.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
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

                <form action="{{ route('super-admin.tenants.store') }}" method="POST">
                    @csrf

                    <h5 class="fw-bold text-primary mb-3">1. Company Profile</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name') }}" required placeholder="e.g. Acme Corp">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Subdomain Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}" required placeholder="acme">
                                <span class="input-group-text small text-muted">.{{ $centralDomain }}</span>
                            </div>
                            <small class="text-muted">Unique tenant identifier used for database and URL routing.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Custom Domain (Optional)</label>
                            <input type="text" name="custom_domain" class="form-control" value="{{ old('custom_domain') }}" placeholder="e.g. fieldforce.acmecorp.com">
                            <small class="text-muted">CNAME mapping point for tenant's white-label domain.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Brand Primary Color</label>
                            <input type="color" name="theme_color" class="form-control form-control-color w-100" value="{{ old('theme_color', '#32c5d2') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">VAT / PAN Number</label>
                            <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number') }}" placeholder="Tax registration number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 555-0199">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Office Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Full physical office location">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    <h5 class="fw-bold text-primary mb-3">2. Initial Company Admin Credentials</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Admin Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="admin@acmecorp.com">
                            <small class="text-muted">Used by company admin to log into the tenant dashboard.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Initial Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                        </div>
                    </div>

                    <h5 class="fw-bold text-primary mb-3">3. Subscription & Status</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Subscription Plan</label>
                            <select name="subscription_plan_id" class="form-select">
                                <option value="">No Plan / Custom Quotas</option>
                                @foreach ($plans as $p)
                                    <option value="{{ $p->id }}" {{ old('subscription_plan_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} (${{ $p->price }}/{{ $p->billing_interval }}) - {{ $p->max_mobile_users > 0 ? $p->max_mobile_users . ' mobile users' : 'Unlimited users' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Workspace Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active (Full Access)</option>
                                <option value="trial" {{ old('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                                <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('super-admin.tenants.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-database me-1"></i> Provision Workspace
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Column -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-circle-info text-primary me-1"></i> What happens automatically:</h6>
                <ol class="small text-muted ps-3 mb-0">
                    <li class="mb-2">A dedicated database (e.g. <code>tenant_[slug]</code>) is created on the MySQL server.</li>
                    <li class="mb-2">All 118+ FieldMA business tables and relations are migrated automatically.</li>
                    <li class="mb-2">Default roles, system permissions, and visit purposes are seeded.</li>
                    <li class="mb-2">The tenant administrator account is initialized with full access.</li>
                    <li>Subdomain and custom domain routes are activated immediately.</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-generate slug from company name if empty
    const nameInput = document.getElementById('company_name');
    const slugInput = document.getElementById('slug');
    nameInput.addEventListener('input', function() {
        if (!slugInput.dataset.manual) {
            slugInput.value = this.value.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        }
    });
    slugInput.addEventListener('input', function() {
        slugInput.dataset.manual = 'true';
    });
</script>
@endsection
