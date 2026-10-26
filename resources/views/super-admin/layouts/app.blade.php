<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Super Admin') - FieldMA SaaS</title>

    <!-- Favicon (Super-Admin Console Only) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/pages/img/favicon-logo-512.png') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('assets/pages/img/favicon-logo-64.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5 CSS (Base utility integration) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CDN with custom Admin theme configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            // 60% Dominant (Canvas / Main Background)
                            canvas: '#F8FAFC', // Slate-50
                            canvasDark: '#F1F5F9', // Slate-100

                            // 30% Structural (Surfaces, Sidebar, Top Navigation, Borders)
                            surface: '#FFFFFF', // Pure White
                            border: '#E2E8F0', // Slate-200
                            borderSubtle: '#F1F5F9', // Slate-100
                            sidebar: {
                                DEFAULT: '#1E1B4B', // Deep Indigo (FieldMA brand)
                                hover: 'rgba(255, 255, 255, 0.08)',
                                active: 'rgba(255, 255, 255, 0.14)',
                                activeBorder: '#C0392B',
                                text: '#94A3B8', // Slate-400
                                textHover: '#FFFFFF',
                                textActive: '#FFFFFF',
                                muted: '#64748B', // Slate-500
                                divider: '#2E2A72',
                            },
                            header: {
                                DEFAULT: '#FFFFFF', // Pure White
                                border: '#E2E8F0', // Slate-200
                                text: '#0F172A',
                                muted: '#64748B',
                            },

                            // 10% Accent (Primary Actions, Alerts & Highlights)
                            primary: {
                                DEFAULT: '#C0392B', // Logo Red
                                hover: '#A93226', // Darker Red
                                active: '#922B21',
                                subtle: '#FEF2F2', // Red-50
                                border: '#FECACA', // Red-200
                            },
                            accent: {
                                DEFAULT: '#F39C12', // Logo Orange/Amber
                                hover: '#D68910',
                                subtle: '#FFFBEB', // Amber-50
                                border: '#FDE68A', // Amber-200
                            },

                            // Typography
                            title: '#0F172A', // Slate-900 (High contrast)
                            body: '#334155', // Slate-700
                            muted: '#64748B', // Slate-500
                            subtle: '#94A3B8', // Slate-400

                            // Semantic Status
                            status: {
                                active: '#10B981',
                                activeBg: '#ECFDF5',
                                activeText: '#047857',
                                activeBorder: '#A7F3D0',

                                trial: '#F59E0B',
                                trialBg: '#FFFBEB',
                                trialText: '#B45309',
                                trialBorder: '#FDE68A',

                                suspended: '#EF4444',
                                suspendedBg: '#FEF2F2',
                                suspendedText: '#B91C1C',
                                suspendedBorder: '#FECACA',

                                inactive: '#64748B',
                                inactiveBg: '#F1F5F9',
                                inactiveText: '#475569',
                                inactiveBorder: '#E2E8F0',
                            }
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
                    },
                    boxShadow: {
                        'admin-card': '0 1px 3px 0 rgba(0, 0, 0, 0.04), 0 1px 2px -1px rgba(0, 0, 0, 0.04)',
                        'admin-card-hover': '0 8px 16px -4px rgba(15, 23, 42, 0.06), 0 2px 4px -2px rgba(15, 23, 42, 0.04)',
                    }
                }
            }
        };
    </script>

    <!-- Super-Admin Scoped CSS Design Tokens (60-30-10 Palette) -->
    <style>
        :root {
            /* 60% Dominant (Canvas / Main Background) */
            --admin-canvas: #F8FAFC;
            --admin-canvas-dark: #F1F5F9;

            /* 30% Structural (Surfaces, Cards, Sidebar & Header) */
            --admin-surface: #FFFFFF;
            --admin-border: #E2E8F0;
            --admin-border-subtle: #F1F5F9;
            --admin-sidebar-bg: #1E1B4B;
            --admin-sidebar-hover: rgba(255, 255, 255, 0.08);
            --admin-sidebar-active: rgba(255, 255, 255, 0.14);
            --admin-sidebar-active-border: #C0392B;
            --admin-sidebar-text: #94A3B8;
            --admin-sidebar-text-active: #FFFFFF;
            --admin-sidebar-muted: #64748B;
            --admin-header-bg: #FFFFFF;
            --admin-header-border: #E2E8F0;

            /* 10% Accent (Primary Actions, Alerts & Highlights) */
            --admin-primary: #C0392B;
            --admin-primary-hover: #A93226;
            --admin-primary-active: #922B21;
            --admin-primary-subtle: #FEF2F2;
            --admin-primary-border: #FECACA;
            --admin-primary-focus: rgba(192, 57, 43, 0.25);

            --admin-accent: #F39C12;
            --admin-accent-hover: #D68910;
            --admin-accent-subtle: #FFFBEB;
            --admin-accent-border: #FDE68A;

            /* Typography & Contrast (WCAG AA Compliant) */
            --admin-text-title: #0F172A;
            --admin-text-body: #334155;
            --admin-text-muted: #64748B;
            --admin-text-subtle: #94A3B8;

            /* Status Badges */
            --admin-status-active-bg: #ECFDF5;
            --admin-status-active-text: #047857;
            --admin-status-active-border: #A7F3D0;

            --admin-status-trial-bg: #FFFBEB;
            --admin-status-trial-text: #B45309;
            --admin-status-trial-border: #FDE68A;

            --admin-status-suspended-bg: #FEF2F2;
            --admin-status-suspended-text: #B91C1C;
            --admin-status-suspended-border: #FECACA;

            --admin-status-inactive-bg: #F1F5F9;
            --admin-status-inactive-text: #475569;
            --admin-status-inactive-border: #E2E8F0;
        }

        body.admin-body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--admin-canvas);
            color: var(--admin-text-body);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        /* 30% Structural: Sidebar Navigation (Deep Indigo #1E1B4B) */
        .admin-sidebar {
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            width: 260px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #141238;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-sidebar-brand {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(15, 23, 42, 0.2);
            text-decoration: none;
        }

        .admin-sidebar-brand .brand-logo-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--admin-primary) 0%, #E74C3C 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(192, 57, 43, 0.3);
        }

        .admin-sidebar-nav {
            padding: 16px 12px;
            flex: 1 1 auto;
            overflow-y: auto;
        }

        .admin-nav-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--admin-sidebar-muted);
            padding: 12px 14px 6px;
        }

        .admin-sidebar-nav .nav-link {
            color: var(--admin-sidebar-text);
            font-weight: 500;
            font-size: 14px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            position: relative;
            text-decoration: none;
        }

        .admin-sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 15px;
            transition: color 0.2s ease;
        }

        .admin-sidebar-nav .nav-link:hover {
            color: var(--admin-sidebar-text-active);
            background-color: var(--admin-sidebar-hover);
        }

        .admin-sidebar-nav .nav-link:hover i {
            color: #FFFFFF;
        }

        .admin-sidebar-nav .nav-link.active {
            color: var(--admin-sidebar-text-active);
            background-color: var(--admin-sidebar-active);
            font-weight: 600;
            border-left: 3px solid var(--admin-primary);
            padding-left: 11px;
        }

        .admin-sidebar-nav .nav-link.active i {
            color: var(--admin-accent);
        }

        .admin-sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(15, 23, 42, 0.25);
        }

        /* 30% Structural: Top Header (Pure White #FFFFFF with border #E2E8F0) */
        .admin-header {
            background-color: var(--admin-header-bg);
            border-bottom: 1px solid var(--admin-header-border);
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 28px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02);
        }

        .admin-layout-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--admin-canvas);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-main-content {
            padding: 28px;
            flex: 1 0 auto;
        }

        /* 30% Structural: Cards & Containers */
        .card,
        .admin-card {
            background-color: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .admin-card-hover:hover {
            box-shadow: 0 8px 16px -4px rgba(15, 23, 42, 0.06), 0 2px 4px -2px rgba(15, 23, 42, 0.04);
            border-color: #CBD5E1;
        }

        .card-header,
        .admin-card-header {
            background-color: var(--admin-surface);
            border-bottom: 1px solid var(--admin-border);
            padding: 16px 22px;
            font-weight: 600;
            color: var(--admin-text-title);
        }

        /* Table Card */
        .admin-table-card {
            background-color: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
        }

        .admin-table-card .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .admin-table-card .table thead th {
            background-color: var(--admin-canvas);
            color: var(--admin-text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 13px 18px;
            border-bottom: 1px solid var(--admin-border);
            white-space: nowrap;
        }

        .admin-table-card .table tbody td {
            padding: 14px 18px;
            color: var(--admin-text-body);
            border-bottom: 1px solid var(--admin-border-subtle);
            font-size: 14px;
        }

        .admin-table-card .table tbody tr:last-child td {
            border-bottom: none;
        }

        .admin-table-card .table tbody tr:hover {
            background-color: #F8FAFC;
        }

        /* 10% Accent: Buttons & CTAs */
        .btn-primary,
        .btn-admin-primary {
            background-color: var(--admin-primary) !important;
            border-color: var(--admin-primary) !important;
            color: #FFFFFF !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            box-shadow: 0 1px 2px 0 rgba(192, 57, 43, 0.2);
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary:hover,
        .btn-admin-primary:hover {
            background-color: var(--admin-primary-hover) !important;
            border-color: var(--admin-primary-hover) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 6px -1px rgba(192, 57, 43, 0.3);
            transform: translateY(-1px);
        }

        .btn-primary:active,
        .btn-admin-primary:active {
            background-color: var(--admin-primary-active) !important;
            border-color: var(--admin-primary-active) !important;
            transform: translateY(0);
        }

        .btn-primary:focus,
        .btn-admin-primary:focus {
            box-shadow: 0 0 0 3px var(--admin-primary-focus) !important;
        }

        .btn-outline-primary {
            color: var(--admin-primary) !important;
            border-color: var(--admin-primary) !important;
            background-color: transparent !important;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.15s ease-in-out;
        }

        .btn-outline-primary:hover {
            background-color: var(--admin-primary) !important;
            color: #FFFFFF !important;
            box-shadow: 0 2px 4px rgba(192, 57, 43, 0.25);
        }

        .btn-admin-accent {
            background-color: var(--admin-accent) !important;
            border-color: var(--admin-accent) !important;
            color: #FFFFFF !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-admin-accent:hover {
            background-color: var(--admin-accent-hover) !important;
            border-color: var(--admin-accent-hover) !important;
            color: #FFFFFF !important;
        }

        .btn-outline-secondary,
        .btn-admin-outline {
            background-color: #FFFFFF !important;
            border: 1px solid var(--admin-border) !important;
            color: var(--admin-text-body) !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 14px;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline-secondary:hover,
        .btn-admin-outline:hover {
            background-color: var(--admin-canvas) !important;
            border-color: #CBD5E1 !important;
            color: var(--admin-text-title) !important;
        }

        /* 10% Accent: KPI Stat Cards */
        .card-stat {
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            background-color: var(--admin-surface);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -4px rgba(15, 23, 42, 0.06);
        }

        /* 10% Accent: Semantic Status Badges */
        .badge-active {
            background-color: var(--admin-status-active-bg) !important;
            color: var(--admin-status-active-text) !important;
            border: 1px solid var(--admin-status-active-border);
            font-weight: 600;
        }

        .badge-trial {
            background-color: var(--admin-status-trial-bg) !important;
            color: var(--admin-status-trial-text) !important;
            border: 1px solid var(--admin-status-trial-border);
            font-weight: 600;
        }

        .badge-suspended {
            background-color: var(--admin-status-suspended-bg) !important;
            color: var(--admin-status-suspended-text) !important;
            border: 1px solid var(--admin-status-suspended-border);
            font-weight: 600;
        }

        .badge-inactive {
            background-color: var(--admin-status-inactive-bg) !important;
            color: var(--admin-status-inactive-text) !important;
            border: 1px solid var(--admin-status-inactive-border);
            font-weight: 600;
        }

        /* Typography & Contrast (WCAG AA Compliance) */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .admin-title {
            color: var(--admin-text-title);
            font-weight: 700;
            letter-spacing: -0.015em;
        }

        .text-muted,
        .admin-muted {
            color: var(--admin-text-muted) !important;
        }

        .text-primary,
        .admin-primary {
            color: var(--admin-primary) !important;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: var(--admin-text-title);
            background-color: #FFFFFF;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px var(--admin-primary-focus);
            outline: none;
        }

        /* Responsive Mobile Drawer */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
            }

            .admin-layout-wrapper {
                margin-left: 0;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(15, 23, 42, 0.5);
                z-index: 1035;
                backdrop-filter: blur(2px);
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    @yield('styles')
</head>

<body class="admin-body">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="toggleAdminSidebar()"></div>

    <!-- ============================================================ -->
    <!-- 30% Structural: <AdminSidebar> Component                     -->
    <!-- Background: Deep Indigo #1E1B4B                              -->
    <!-- ============================================================ -->
    <aside id="adminSidebar" class="admin-sidebar">
        <!-- Brand / Identity -->
        <a href="{{ route('super-admin.dashboard') }}"
            class="admin-sidebar-brand d-flex align-items-center justify-content-between text-decoration-none">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-white rounded px-2 py-1 d-inline-flex align-items-center shadow-sm"
                    style="height: 38px;">
                    <img src="{{ asset('assets/pages/img/logo-fieldma.png') }}" alt="FieldMA"
                        style="height: 24px; max-width: 130px; object-fit: contain;">
                </div>
            </div>
            <span class="badge text-uppercase"
                style="background-color: rgba(243, 156, 18, 0.18); color: var(--admin-accent); font-size: 10px; padding: 3px 6px;">
                SaaS Hub
            </span>
        </a>

        <!-- Navigation Menu -->
        <nav class="admin-sidebar-nav">
            <div class="admin-nav-section-label">Core Management</div>
            <ul class="nav flex-column mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('super-admin.dashboard') }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super-admin.tenants*') ? 'active' : '' }}"
                        href="{{ route('super-admin.tenants.index') }}">
                        <i class="fa-solid fa-building"></i>
                        <span>Companies & Tenants</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super-admin.plans*') ? 'active' : '' }}"
                        href="{{ route('super-admin.plans.index') }}">
                        <i class="fa-solid fa-cubes"></i>
                        <span>Subscription Plans</span>
                    </a>
                </li>
            </ul>

            <div class="admin-nav-section-label">System</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('super-admin.tenants.create') }}">
                        <i class="fa-solid fa-plus-circle text-primary"></i>
                        <span>Provision Company</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div class="admin-sidebar-footer">
            <div class="d-flex align-items-center justify-content-between text-muted small">
                <span>FieldMA SaaS</span>
                <span class="badge bg-dark border border-secondary font-monospace"
                    style="font-size: 10px; color: #94A3B8;">v2.0 LTS</span>
            </div>
        </div>
    </aside>

    <!-- ============================================================ -->
    <!-- Main Wrapper: Header + Dynamic Content Canvas                -->
    <!-- Canvas Background: #F8FAFC (60% Dominant)                    -->
    <!-- ============================================================ -->
    <div class="admin-layout-wrapper">
        <!-- 30% Structural: <AdminHeader> (Pure White #FFFFFF with border #E2E8F0) -->
        <header class="admin-header justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm btn-outline-secondary d-lg-none" onclick="toggleAdminSidebar()"
                    aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge"
                        style="background-color: var(--admin-primary-subtle); color: var(--admin-primary); border: 1px solid var(--admin-primary-border); font-size: 11px;">
                        Platform Central
                    </span>
                    <span class="text-muted d-none d-sm-inline small">
                        <i class="fa-solid fa-circle text-success fa-xs me-1"></i> Multi-Tenancy Engine Online
                    </span>
                </div>
            </div>

            <!-- Super-Admin Profile & Logout -->
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2 px-2 py-1 rounded bg-light border border-slate-200">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width: 32px; height: 32px; background-color: var(--admin-sidebar-bg); font-size: 13px;">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="d-flex flex-column text-start d-none d-sm-block">
                        <span class="fw-semibold small" style="color: var(--admin-text-title); line-height: 1.1;">
                            {{ Auth::guard('super_admin')->user()->name ?? 'Super Admin' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('super-admin.logout') }}" method="POST" class="d-inline mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary" title="Sign out of Super Admin">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="d-none d-md-inline ms-1">Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Dynamic Main Content Canvas (60% Background #F8FAFC) -->
        <main class="admin-main-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                    style="background-color: #ECFDF5; color: #065F46; border-left: 4px solid #10B981 !important;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert"
                    style="background-color: #FEF2F2; color: #991B1B; border-left: 4px solid var(--admin-primary) !important;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('show');
            if (backdrop) backdrop.classList.toggle('show');
        }
    </script>
    @yield('scripts')
</body>

</html>
