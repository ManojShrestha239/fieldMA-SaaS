Here is how you can check if a module/feature flag or a user permission is enabled in both Blade views and Web/PHP code:
──────

### 1. In Blade Views (.blade.php)

#### A. Checking Module Feature Flags (Tenant Plan)

Use the @feature(...) blade directive:
{{-- Check if a module is enabled in the company's plan --}}
@feature('notices')

<div>Notices module is active for this company.</div>
@endfeature

    {{-- Check a specific sub-feature using dot notation --}}
    @feature('dealer_orders.new_order')
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Create New Order</a>
    @endfeature

#### B. Checking User Permissions (Spatie RBAC)

Use Laravel's standard @can(...) directive:
@can('notice')
<a href="{{ route('notices.index') }}">View Notices</a>
@endcan

#### C. Best Practice: Combining Both (Plan Module + User Permission)

To ensure the module is purchased in the plan and the logged-in user's role has permission:
@feature('notices')
@can('notice')

<li class="nav-item">
<a href="{{ route('notices.index') }}" class="nav-link">
<i class="fa fa-bell"></i> Notices
</a>
</li>
@endcan
@endfeature
──────

### 2. In PHP / Controllers / Web Backend

#### A. Checking Module Feature Flags

You can check directly using the active tenant or the helper:

    use App\Utilities\Helpers;

    // 1. Using tenant()
    if (tenant()->hasFeature('notices')) {
        // Notices module is enabled
    }

    // 2. Sub-feature check (dot notation or two arguments)
    if (tenant()->hasFeature('dealer_orders.new_order')) {
        // User's company plan allows creating dealer orders
    }
    // or:
    if (tenant()->hasFeature('dealer_orders', 'new_order')) {
        // ...
    }

    // 3. Using the global helper (safe even outside tenant context)
    if (Helpers::hasFeature('notices')) {
        // ...
    }

#### B. Checking User Permissions (Spatie)

    // Check on current authenticated user
    if (auth()->user()->can('notice')) {
        // User has notice permission
    }

    // Or directly on any User instance
    if ($user->hasPermissionTo('notice')) {
        // ...
    }

#### C. In Controller Action (Combined Guard)

    public function create()
    {
        // 1. Guard by Company Plan Module Flag
        abort_unless(tenant()->hasFeature('dealer_orders.new_order'), 403, 'Module not included in your subscription
        plan.');

        // 2. Guard by User Permission
        $this->authorize('order');

        return view('orders.create');
    }

──────

### 3. In Web Routes (tenant_web.php)

Use the feature:<key> middleware on individual routes or entire route groups:

    // Single module gate
    Route::middleware(['auth', 'feature:notices'])->group(function () {
        Route::resource('notice', NoticeController::class);
    });

    // Specific sub-feature gate
    Route::middleware(['auth', 'feature:dealer_orders.new_order'])->group(function () {
        Route::get('/orders/new', [OrderController::class, 'create'])->name('orders.create');
    });

    // Pipe '|' for OR conditions (if either dealer_orders OR retailer_orders is enabled)
    Route::middleware(['auth', 'feature:dealer_orders|retailer_orders'])->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });
