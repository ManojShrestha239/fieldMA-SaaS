# Feature Flags & Permissions Cheat Sheet

This guide explains how to check module feature flags (Tenant Plan limits) and user permissions (Spatie RBAC) across Blade views, PHP controllers, and Web routes.

---

## 💡 Quick Overview

| Context | Module Feature Flag | User Permission | Combined Best Practice |
| :--- | :--- | :--- | :--- |
| **Blade Views** | `@feature('module')` | `@can('permission')` | Wrap `@can` inside `@feature` |
| **PHP / Backend** | `tenant()->hasFeature('module')` | `auth()->user()->can('permission')` | Check feature first, then authorize permission |
| **Routes** | `middleware('feature:module')` | `middleware('can:permission')` | Stack both middleware on route groups |

---

## 1. In Blade Views (`.blade.php`)

### A. Checking Module Feature Flags (Tenant Plan)
Use the `@feature(...)` blade directive to show UI elements only if the active tenant's plan includes the module.

```blade
{{-- Check if a top-level module is enabled --}}
@feature('notices')
    <div>Notices module is active for this company.</div>
@endfeature

{{-- Check a specific sub-feature using dot notation --}}
@feature('dealer_orders.new_order')
    <a href="{{ route('orders.create') }}" class="btn btn-primary">Create New Order</a>
@endfeature
```

### B. Checking User Permissions (Spatie RBAC)
Use Laravel's standard `@can(...)` directive to check user role capabilities.

```blade
@can('notice')
    <a href="{{ route('notices.index') }}">View Notices</a>
@endcan
```

### C. Best Practice: Combining Both
To ensure a section is rendered only when **the plan includes the module** AND **the user has permission**:

```blade
@feature('notices')
    @can('notice')
        <li class="nav-item">
            <a href="{{ route('notices.index') }}" class="nav-link">
                <i class="fa fa-bell"></i> Notices
            </a>
        </li>
    @endcan
@endfeature
```

---

## 2. In PHP / Controllers / Web Backend

### A. Checking Module Feature Flags

```php
use App\Utilities\Helpers;

// 1. Using tenant() helper
if (tenant()->hasFeature('notices')) {
    // Notices module is enabled
}

// 2. Sub-feature check (dot notation or two arguments)
if (tenant()->hasFeature('dealer_orders.new_order')) {
    // Plan allows creating dealer orders
}

// Alternatively:
if (tenant()->hasFeature('dealer_orders', 'new_order')) {
    // ...
}

// 3. Using the global helper (safe outside tenant context)
if (Helpers::hasFeature('notices')) {
    // ...
}

// 4. Getting enabled features for a given module (e.g. dealer_orders)
$orderFeatures = tenant()->getEnabledFeatures('dealer_orders');
// Returns: ['new_order', 'order_history', 'saved_orders'] (or active subset)

// Or get all enabled modules and features as a map:
$allFeatures = tenant()->getEnabledFeatures();

// Global helper alias:
$enabled = tenant_enabled_features('dealer_orders');
```

### B. Checking User Permissions (Spatie)

```php
// Check on authenticated user
if (auth()->user()->can('notice')) {
    // User has notice permission
}

// Check directly on a specific User instance
if ($user->hasPermissionTo('notice')) {
    // ...
}
```

### C. In Controller Actions (Combined Guard)

Always check company plan feature flags first, followed by authorization:

```php
public function create()
{
    // 1. Guard by Company Plan Module Flag
    abort_unless(
        tenant()->hasFeature('dealer_orders.new_order'), 
        403, 
        'Module not included in your subscription plan.'
    );

    // 2. Guard by User Permission
    $this->authorize('order');

    return view('orders.create');
}
```

---

## 3. In Web Routes (`tenant_web.php`)

Apply the `feature:<key>` middleware to individual routes or route groups.

```php
// 1. Single module gate
Route::middleware(['auth', 'feature:notices'])->group(function () {
    Route::resource('notice', NoticeController::class);
});

// 2. Specific sub-feature gate
Route::middleware(['auth', 'feature:dealer_orders.new_order'])->group(function () {
    Route::get('/orders/new', [OrderController::class, 'create'])->name('orders.create');
});

// 3. Pipe '|' for OR conditions (if dealer_orders OR retailer_orders is enabled)
Route::middleware(['auth', 'feature:dealer_orders|retailer_orders'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});
```