@extends('super-admin.layouts.app')

@section('title', 'Create Subscription Plan')

@section('content')
    <div class="mb-4">
        <a href="{{ route('super-admin.plans.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Plans
        </a>
        <h3 class="fw-bold mb-1">Create Subscription Plan</h3>
        <p class="text-muted small mb-0">Set price, limits, and toggle module availability for this tier.</p>
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

            <form action="{{ route('super-admin.plans.store') }}" method="POST">
                @csrf

                <h5 class="fw-bold text-primary mb-3">1. Plan Overview</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Plan Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                            placeholder="e.g. Professional">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Slug (Optional)</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}"
                            placeholder="professional">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Price (NPR) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control"
                            value="{{ old('price', '0.00') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Billing Interval <span
                                class="text-danger">*</span></label>
                        <select name="billing_interval" class="form-select">
                            <option value="monthly" {{ old('billing_interval') === 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                            <option value="yearly" {{ old('billing_interval') === 'yearly' ? 'selected' : '' }}>Yearly
                            </option>
                            <option value="lifetime" {{ old('billing_interval') === 'lifetime' ? 'selected' : '' }}>Lifetime
                            </option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief summary of who this plan is for">{{ old('description') }}</textarea>
                    </div>
                </div>

                <h5 class="fw-bold text-primary mb-3">2. User Quotas & Limits</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Max Mobile Users (Field Sales) <span
                                class="text-danger">*</span></label>
                        <input type="number" name="max_mobile_users" class="form-control"
                            value="{{ old('max_mobile_users', 10) }}" required min="0">
                        <small class="text-muted">Enter 0 for unlimited field users.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Max Web Admins <span
                                class="text-danger">*</span></label>
                        <input type="number" name="max_admin_users" class="form-control"
                            value="{{ old('max_admin_users', 2) }}" required min="0">
                        <small class="text-muted">Enter 0 for unlimited portal admins.</small>
                    </div>
                </div>

                <h5 class="fw-bold text-primary mb-3">3. Module Feature Flags</h5>
                <p class="text-muted small mb-3">Select which modules are accessible by companies on this plan:</p>
                <div class="row g-2 mb-4">
                    @foreach ($availableFeatures as $fKey => $fLabel)
                        <div class="col-md-6">
                            <div class="form-check p-2 border rounded bg-light">
                                <input class="form-check-input ms-0 me-2" type="checkbox"
                                    name="features[{{ $fKey }}]" value="1" id="feat_{{ $fKey }}"
                                    checked>
                                <label class="form-check-label fw-semibold small" for="feat_{{ $fKey }}">
                                    {{ $fLabel }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-4 form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                    <label class="form-check-label small fw-semibold" for="is_active">
                        Plan is Active and available for selection
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('super-admin.plans.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Create Plan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
