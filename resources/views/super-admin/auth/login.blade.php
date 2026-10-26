<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin Login - FieldMA SaaS</title>

    <!-- Favicon (Super-Admin Console Only) -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/pages/img/favicon-superadmin.svg') }}">
    <link rel="alternate icon" type="image/png" href="{{ asset('assets/pages/img/favicon-superadmin.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1E1B4B 0%, #0F172A 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #334155;
        }

        .login-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.3), 0 8px 10px -6px rgba(15, 23, 42, 0.2);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }

        .brand-badge {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #C0392B 0%, #A93226 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            font-size: 24px;
            box-shadow: 0 4px 6px -1px rgba(192, 57, 43, 0.3);
            margin-bottom: 16px;
        }

        .btn-submit {
            background-color: #C0392B !important;
            border-color: #C0392B !important;
            color: #FFFFFF !important;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(192, 57, 43, 0.2);
        }

        .btn-submit:hover {
            background-color: #A93226 !important;
            border-color: #A93226 !important;
            box-shadow: 0 4px 6px -1px rgba(192, 57, 43, 0.3);
            transform: translateY(-1px);
        }

        .form-control:focus {
            border-color: #C0392B;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.25);
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="mb-3">
                <img src="{{ asset('assets/pages/img/logo-fieldma.png') }}" alt="FieldMA"
                    style="max-height: 44px; width: auto; object-fit: contain;">
            </div>
            <h4 class="fw-bold mb-1" style="color: #0F172A; letter-spacing: -0.02em;">Platform Operator</h4>
            <p class="text-muted small mb-0">Super-Admin Central Console</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small mb-3 border-0"
                style="background-color: #FEF2F2; color: #991B1B; border-left: 3px solid #C0392B !important;">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('super-admin.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold" style="color: #334155;">Operator Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i
                            class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0"
                        value="{{ old('email') }}" required autofocus placeholder="superadmin@fieldma.com">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold" style="color: #334155;">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i
                            class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 ps-0" required
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Access Admin Console
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top border-slate-200">
            <small class="text-muted" style="font-size: 11px;">
                &copy; {{ date('Y') }} FieldMA Multi-Tenant SaaS Engine. All rights reserved.
            </small>
        </div>
    </div>
</body>

</html>
