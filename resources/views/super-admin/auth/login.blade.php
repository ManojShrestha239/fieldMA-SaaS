<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Super Admin Login - FieldMA SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 56px; height: 56px;">
                <i class="fa-solid fa-shield-halved fa-xl"></i>
            </div>
            <h3 class="fw-bold mb-1">Super Admin</h3>
            <p class="text-muted small">FieldMA SaaS Platform Central Management</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('super-admin.login.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="superadmin@fieldma.com">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small" for="remember">Remember me on this machine</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                Sign in to Dashboard <i class="fa-solid fa-arrow-right ms-1"></i>
            </button>
        </form>
    </div>
</body>
</html>
