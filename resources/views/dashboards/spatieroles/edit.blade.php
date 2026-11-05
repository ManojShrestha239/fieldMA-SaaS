@extends('dashboards.blank')

@section('content')
    <style>
        .module-feature-card {
            border: 1px solid #e7ecf1;
            border-radius: 4px;
            padding: 12px 14px;
            background: #fff;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }
        .module-feature-card:hover {
            border-color: #3598dc;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .module-master-label {
            font-weight: 600;
            font-size: 13px;
            color: #333;
            margin-bottom: 2px;
            cursor: pointer;
        }
        .module-details-text {
            font-size: 11px;
            color: #888;
            margin: 0;
        }
        .sub-features-wrapper {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #e7ecf1;
            padding-left: 12px;
            border-left: 2px solid #3598dc;
            transition: opacity 0.2s ease;
        }
        .sub-feature-item {
            margin-bottom: 4px;
        }
        .sub-feature-item label {
            font-size: 12px;
            color: #555;
            font-weight: 500;
            cursor: pointer;
            margin-left: 4px;
        }
        .core-perm-badge {
            display: inline-block;
            margin: 3px 6px 3px 0;
            background: #f1f4f7;
            border: 1px solid #e1e5ec;
            border-radius: 3px;
            padding: 4px 8px;
        }
        .core-perm-badge label {
            font-size: 12px;
            font-weight: 500;
            color: #444;
            margin: 0 0 0 4px;
            cursor: pointer;
        }
    </style>

    <div class="page-content1">
        <div class="breadcrumbs">
            <h1>Edit Role: {{ $role->name }}</h1>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".page-sidebar">
                <span class="sr-only">Toggle navigation</span>
                <span class="toggle-icon">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </span>
            </button>
        </div>

        <div class="page-content-container">
            <div class="page-content-row" id="replecable">
                <div class="page-content-col">
                    <div style="margin-bottom: 15px;">
                        <a href="{{ route('role-permission.index') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Roles List
                        </a>
                    </div>

                    @if (session('Status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <b>{{ session('Status') }}</b>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                @foreach ($errors->all() as $error)
                                    <p class="alert alert-danger">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-9 col-md-offset-1">
                            <div class="portlet light portlet-fit bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-pencil font-red"></i>
                                        <span class="caption-subject font-red bold uppercase">Modify Role Permissions</span>
                                    </div>
                                    <div class="actions">
                                        <span class="badge badge-info" style="font-size: 11px;">
                                            {{ count($tenantFeatures) }} modules unlocked by your plan
                                        </span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <form method="POST" action="{{ route('role-permission.update', $role->id) }}">
                                        {{ method_field('PATCH') }}
                                        @csrf

                                        <div class="form-group" style="margin-bottom: 20px;">
                                            <label for="name" style="font-weight: 600; font-size: 13px;">Role Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                name="name" id="name" value="{{ old('name', $role->name) }}" required autofocus>
                                            @if ($errors->has('name'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Section 1: Core Administration & Setup Permissions -->
                                        <div style="background: #fbfbfb; border: 1px solid #e7ecf1; border-radius: 4px; padding: 12px; margin-bottom: 20px;">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                                <h5 style="margin: 0; font-weight: 700; color: #2c3e50; font-size: 13px;">
                                                    <i class="fa fa-cogs text-primary"></i> 1. Core Administration & Setup
                                                </h5>
                                                <small class="text-muted">General portal setup privileges</small>
                                            </div>
                                            <div style="display: flex; flex-wrap: wrap;">
                                                @foreach ($corePermissions as $cKey => $cConfig)
                                                    @php
                                                        $isCoreChecked = is_array(old('core_permissions'))
                                                            ? in_array($cKey, old('core_permissions'))
                                                            : in_array($cKey, $rolePermissionNames);
                                                    @endphp
                                                    <div class="core-perm-badge">
                                                        <label style="margin: 0; display: flex; align-items: center; gap: 4px;">
                                                            <input type="checkbox" name="core_permissions[]" value="{{ $cKey }}"
                                                                {{ $isCoreChecked ? 'checked' : '' }}>
                                                            <span>{{ is_array($cConfig) ? ($cConfig['label'] ?? $cKey) : $cConfig }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Section 2: Module Feature Flags (Strictly bounded by Tenant Plan) -->
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-top: 1px solid #e7ecf1; padding-top: 15px;">
                                            <div>
                                                <h5 style="margin: 0; font-weight: 700; color: #2c3e50; font-size: 13px;">
                                                    <i class="fa fa-cubes text-primary"></i> 2. Module Feature Flags
                                                </h5>
                                                <p style="margin: 2px 0 0 0; font-size: 11px; color: #777;">
                                                    Assign access to modules and sub-features available to your organization:
                                                </p>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-default btn-xs" id="btnSelectAllTenantFeatures">
                                                    <i class="fa fa-check-square-o"></i> Select All
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs" id="btnDeselectAllTenantFeatures">
                                                    <i class="fa fa-square-o"></i> Deselect All
                                                </button>
                                            </div>
                                        </div>

                                        @if (empty($tenantFeatures))
                                            <div class="alert alert-warning" style="font-size: 12px;">
                                                <i class="fa fa-exclamation-triangle"></i> No additional modules are currently enabled for your company subscription plan.
                                            </div>
                                        @else
                                            <div class="row">
                                                @foreach ($tenantFeatures as $fKey => $fConfig)
                                                    @php
                                                        $label = is_array($fConfig) ? ($fConfig['label'] ?? ucwords(str_replace('_', ' ', $fKey))) : $fConfig;
                                                        $details = is_array($fConfig) ? ($fConfig['details'] ?? '') : '';
                                                        $subFeatures = is_array($fConfig) ? ($fConfig['sub_features'] ?? []) : [];
                                                        $hasSubs = !empty($subFeatures);

                                                        // Check if this module is checked for the role
                                                        $basePerms = config("features.permissions_map.{$fKey}", []);
                                                        $hasBasePerm = !empty(array_intersect($basePerms, $rolePermissionNames));
                                                        $hasCanonical = in_array($fKey, $rolePermissionNames);
                                                        $defaultChecked = $hasBasePerm || $hasCanonical;

                                                        $isModuleChecked = old("features.{$fKey}.enabled") !== null
                                                            ? old("features.{$fKey}.enabled") == 1
                                                            : $defaultChecked;
                                                    @endphp
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="module-feature-card" data-feature="{{ $fKey }}">
                                                            <div style="display: flex; align-items: flex-start; gap: 8px;">
                                                                <input type="checkbox" class="module-master-checkbox"
                                                                    name="features[{{ $fKey }}][enabled]" value="1"
                                                                    id="feat_{{ $fKey }}"
                                                                    {{ $isModuleChecked ? 'checked' : '' }}
                                                                    style="margin-top: 3px;">
                                                                <div style="flex-grow: 1;">
                                                                    <label class="module-master-label" for="feat_{{ $fKey }}">
                                                                        {{ $label }}
                                                                        @if ($hasSubs)
                                                                            <span class="badge badge-default" style="font-size: 10px; font-weight: normal; background: #eef1f5; color: #337ab7;">
                                                                                {{ count($subFeatures) }} subs
                                                                            </span>
                                                                        @endif
                                                                    </label>
                                                                    @if ($details)
                                                                        <p class="module-details-text">{{ $details }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            @if ($hasSubs)
                                                                <div class="sub-features-wrapper">
                                                                    @foreach ($subFeatures as $subKey)
                                                                        @php
                                                                            $subPermName = "{$fKey}.{$subKey}";
                                                                            $subDefaultChecked = in_array($subPermName, $rolePermissionNames)
                                                                                || ($defaultChecked && !collect($subFeatures)->contains(fn($s) => in_array("{$fKey}.{$s}", $rolePermissionNames)));

                                                                            $isSubChecked = old("features.{$fKey}.sub_features.{$subKey}") !== null
                                                                                ? old("features.{$fKey}.sub_features.{$subKey}") == 1
                                                                                : ($isModuleChecked && $subDefaultChecked);
                                                                        @endphp
                                                                        <div class="sub-feature-item">
                                                                            <label>
                                                                                <input type="checkbox" class="module-sub-checkbox"
                                                                                    name="features[{{ $fKey }}][sub_features][{{ $subKey }}]"
                                                                                    value="1"
                                                                                    id="sub_{{ $fKey }}_{{ $subKey }}"
                                                                                    {{ $isSubChecked ? 'checked' : '' }}>
                                                                                {{ ucwords(str_replace('_', ' ', $subKey)) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; display: flex; gap: 10px;">
                                            <button type="submit" class="btn btn-primary" style="width: 180px;">
                                                <i class="fa fa-save"></i> Update Role
                                            </button>
                                            <a href="{{ route('role-permission.index') }}" class="btn btn-default">
                                                Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    subWrapper.style.opacity = '0.4';
                }
            }

            cards.forEach(card => {
                const master = card.querySelector('.module-master-checkbox');
                const subs = card.querySelectorAll('.module-sub-checkbox');

                if (master) {
                    master.addEventListener('change', function() {
                        subs.forEach(s => s.checked = this.checked);
                        updateCardVisuals(card);
                    });
                }

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

            // Select All / Deselect All
            document.getElementById('btnSelectAllTenantFeatures')?.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.module-master-checkbox').forEach(m => m.checked = true);
                document.querySelectorAll('.module-sub-checkbox').forEach(s => s.checked = true);
                cards.forEach(updateCardVisuals);
            });

            document.getElementById('btnDeselectAllTenantFeatures')?.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.module-master-checkbox').forEach(m => m.checked = false);
                document.querySelectorAll('.module-sub-checkbox').forEach(s => s.checked = false);
                cards.forEach(updateCardVisuals);
            });
        });
    </script>
@endsection
