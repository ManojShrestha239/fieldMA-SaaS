<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Super Admin') - FieldMA SaaS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .navbar-custom {
            background-color: #0f172a;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
        }
        .sidebar .nav-link {
            color: #475569;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 6px;
            margin: 4px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .nav-link:hover {
            color: #0f172a;
            background-color: #f1f5f9;
        }
        .sidebar .nav-link.active {
            color: #2563eb;
            background-color: #eff6ff;
            font-weight: 600;
        }
        .content-area {
            padding: 30px;
        }
        .card-stat {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-2px);
        }
        .badge-active { background-color: #dcfce7; color: #166534; }
        .badge-suspended { background-color: #fee2e2; color: #991b1b; }
        .badge-trial { background-color: #fef9c3; color: #854d0e; }
        .badge-inactive { background-color: #f1f5f9; color: #475569; }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom px-4 sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('super-admin.dashboard') }}">
                <i class="fa-solid fa-layer-group text-primary"></i> FieldMA <span class="badge bg-primary text-white ms-1 font-monospace" style="font-size: 11px;">SaaS Hub</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-light small">
                    <i class="fa fa-user-shield me-1"></i> {{ Auth::guard('super_admin')->user()->name ?? 'Super Admin' }}
                </span>
                <form action="{{ route('super-admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <ul class="nav flex-column py-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}" href="{{ route('super-admin.dashboard') }}">
                            <i class="fa-solid fa-chart-pie"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.tenants*') ? 'active' : '' }}" href="{{ route('super-admin.tenants.index') }}">
                            <i class="fa-solid fa-building"></i> Companies & Tenants
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.plans*') ? 'active' : '' }}" href="{{ route('super-admin.plans.index') }}">
                            <i class="fa-solid fa-cube"></i> Subscription Plans
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content-area">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
