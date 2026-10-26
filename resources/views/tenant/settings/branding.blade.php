@extends('dashboards.universal')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase">Company Settings & Branding</span>
                </div>
            </div>
            <div class="portlet-body form">
                @if (session('Status'))
                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        {{ session('Status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <ul class="margin-bottom-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tenant.settings.branding.update') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    @method('PUT')

                    <div class="form-body">
                        <h4 class="form-section">Company Information</h4>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Name <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $tenant->company_name) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Official Email <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" value="{{ old('email', $tenant->email) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Phone Number</label>
                            <div class="col-md-6">
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $tenant->phone) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">VAT / PAN Number</label>
                            <div class="col-md-6">
                                <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $tenant->vat_number) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Address</label>
                            <div class="col-md-6">
                                <textarea name="address" rows="3" class="form-control">{{ old('address', $tenant->address) }}</textarea>
                            </div>
                        </div>

                        <h4 class="form-section">Branding & Theme</h4>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Current Logo</label>
                            <div class="col-md-6">
                                <div style="padding: 10px; background: #fafafa; border: 1px solid #ddd; display: inline-block; border-radius: 4px;">
                                    <img src="{{ $tenant->getLogoUrl() }}" alt="Logo" style="max-height: 70px; max-width: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Upload New Logo</label>
                            <div class="col-md-6">
                                <input type="file" name="logo" accept="image/*" class="form-control">
                                <span class="help-block">Recommended format: PNG or SVG with transparent background (max 2MB).</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Current Favicon</label>
                            <div class="col-md-6">
                                <div style="padding: 6px; background: #fafafa; border: 1px solid #ddd; display: inline-block; border-radius: 4px;">
                                    <img src="{{ $tenant->getFaviconUrl() }}" alt="Favicon" style="width: 32px; height: 32px; object-fit: contain;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Upload New Favicon</label>
                            <div class="col-md-6">
                                <input type="file" name="favicon" accept=".ico,image/x-icon,image/png,image/svg+xml,image/webp,image/jpeg" class="form-control">
                                <span class="help-block">Recommended format: 32x32 or 64x64 ICO or PNG (max 1MB).</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Theme Primary Color</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="color" name="theme_color" id="theme_color_picker" class="form-control" value="{{ old('theme_color', $tenant->getThemeColor()) }}" style="height: 38px; padding: 2px;">
                                </div>
                                <span class="help-block">Changes the primary color of navigation, buttons, and highlights.</span>
                            </div>
                        </div>

                        <h4 class="form-section">Notifications & FCM</h4>

                        <div class="form-group">
                            <label class="col-md-3 control-label">FCM Notification Topic</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $tenant->getFcmTopic() }}" disabled>
                                <span class="help-block">This push notification topic is automatically isolated for your company's mobile field agents.</span>
                            </div>
                        </div>

                        <h4 class="form-section">Subscription & Quota</h4>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Active Plan</label>
                            <div class="col-md-6">
                                <p class="form-control-static">
                                    <strong>{{ $plan ? $plan->name : 'Standard' }}</strong>
                                    <span class="label label-success">Active</span>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Field Staff / Mobile Users Limit</label>
                            <div class="col-md-6">
                                @php
                                    $currentUsersCount = \App\Models\Mobileuser::count();
                                    $maxUsers = $plan ? (int)$plan->max_mobile_users : 0;
                                @endphp
                                <p class="form-control-static">
                                    <strong>{{ $currentUsersCount }}</strong> used of 
                                    <strong>{{ $maxUsers > 0 ? $maxUsers : 'Unlimited' }}</strong> allowed
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn green"><i class="fa fa-save"></i> Save Changes</button>
                                <a href="/dashboard" class="btn default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
