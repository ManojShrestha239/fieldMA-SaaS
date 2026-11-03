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

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold text-primary mb-0">3. Module Feature Flags</h5>
                        <p class="text-muted small mb-0">Select which modules and sub-features are included in this plan tier:</p>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" id="btnSelectAllFeatures">
                            <i class="fa-solid fa-check-double me-1"></i> Select All
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btnDeselectAllFeatures">
                            <i class="fa-solid fa-xmark me-1"></i> Deselect All
                        </button>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    @foreach ($availableFeatures as $fKey => $fConfig)
                        @php
                            $label = is_array($fConfig) ? ($fConfig['label'] ?? ucwords(str_replace('_', ' ', $fKey))) : $fConfig;
                            $details = is_array($fConfig) ? ($fConfig['details'] ?? '') : '';
                            $subFeatures = is_array($fConfig) ? ($fConfig['sub_features'] ?? []) : [];
                            $hasSubs = !empty($subFeatures);
                        @endphp
                        <div class="col-12 col-md-6">
                            <div class="card h-100 border rounded shadow-none bg-white p-3 module-feature-card" data-feature="{{ $fKey }}">
                                <div class="form-check d-flex align-items-start gap-2 mb-0">
                                    <input class="form-check-input mt-1 module-master-checkbox" type="checkbox"
                                        name="features[{{ $fKey }}][enabled]" value="1"
                                        id="feat_{{ $fKey }}"
                                        checked>
                                    <div class="flex-grow-1">
                                        <label class="form-check-label fw-bold text-dark d-block mb-1" for="feat_{{ $fKey }}">
                                            {{ $label }}
                                            @if ($hasSubs)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-1 fw-normal" style="font-size: 11px;">
                                                    {{ count($subFeatures) }} sub-features
                                                </span>
                                            @endif
                                        </label>
                                        @if ($details)
                                            <p class="text-muted small mb-0">{{ $details }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if ($hasSubs)
                                    <div class="mt-3 pt-2 border-top sub-features-wrapper">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;">Sub-features</span>
                                        </div>
                                        <div class="d-flex flex-column gap-2 ps-2 border-start border-2 border-primary-subtle">
                                            @foreach ($subFeatures as $subKey)
                                                <div class="form-check">
                                                    <input class="form-check-input module-sub-checkbox" type="checkbox"
                                                        name="features[{{ $fKey }}][sub_features][{{ $subKey }}]"
                                                        value="1"
                                                        id="sub_{{ $fKey }}_{{ $subKey }}"
                                                        checked>
                                                    <label class="form-check-label small fw-medium text-secondary" for="sub_{{ $fKey }}_{{ $subKey }}">
                                                        {{ ucwords(str_replace('_', ' ', $subKey)) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
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

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.module-feature-card');

                function updateCardVisuals(card) {
                    const master = card.querySelector('.module-master-checkbox');
                    const subWrapper = card.querySelector('.sub-features-wrapper');
                    if (!master || !subWrapper) return;

                    if (master.checked) {
                        subWrapper.style.opacity = '1';
                    } else {
                        subWrapper.style.opacity = '0.45';
                    }
                }

                cards.forEach(card => {
                    const master = card.querySelector('.module-master-checkbox');
                    const subs = card.querySelectorAll('.module-sub-checkbox');

                    // When main feature is toggled: auto select or deselect all sub features
                    if (master) {
                        master.addEventListener('change', function() {
                            if (this.checked) {
                                subs.forEach(s => s.checked = true);
                            } else {
                                subs.forEach(s => s.checked = false);
                            }
                            updateCardVisuals(card);
                        });
                    }

                    // When any sub-feature is selected, AUTO SELECT THE MAIN FEATURE
                    subs.forEach(sub => {
                        sub.addEventListener('change', function() {
                            if (this.checked && master) {
                                master.checked = true;
                            }
                            updateCardVisuals(card);
                        });
                    });

                    updateCardVisuals(card);
                });

                // SELECT ALL MODULES & SUB-FEATURES
                document.getElementById('btnSelectAllFeatures')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.module-master-checkbox').forEach(m => m.checked = true);
                    document.querySelectorAll('.module-sub-checkbox').forEach(s => s.checked = true);
                    cards.forEach(updateCardVisuals);
                });

                // DESELECT ALL MODULES & SUB-FEATURES
                document.getElementById('btnDeselectAllFeatures')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.module-master-checkbox').forEach(m => m.checked = false);
                    document.querySelectorAll('.module-sub-checkbox').forEach(s => s.checked = false);
                    cards.forEach(updateCardVisuals);
                });
            });
        </script>
    @endsection
@endsection
