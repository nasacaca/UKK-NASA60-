<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan SMA Nusantara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1976D2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            top: -200px;
            right: -100px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -100px;
            left: -100px;
        }

        .auth-card {
            background: #fff;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            position: relative;
            z-index: 1;
        }

        .auth-logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1565C0, #42A5F5);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 20px rgba(21, 101, 192, 0.3);
        }

        .form-control {
            border-color: #dde8ff;
            border-radius: 12px;
            padding: 12px 16px;
        }

        .form-control:focus {
            border-color: #1565C0;
            box-shadow: 0 0 0 0.25rem rgba(21, 101, 192, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #1565C0, #1976D2);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            letter-spacing: 0.03em;
            box-shadow: 0 4px 15px rgba(21, 101, 192, 0.3);
            transition: all 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(21, 101, 192, 0.4);
        }

        .input-group-text {
            background: #EEF2FF;
            border-color: #dde8ff;
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="d-flex align-items-center justify-content-center">
            <img src="{{ asset('image/logo.png') }}" alt="Logo"
                style="width: 50px; height: 50px; object-fit: cover;" class="rounded shadow-sm">
        </div>
        <h4 class="text-center fw-bold mb-1" style="color:#0D47A1;">Perpustakaan</h4>
        <p class="text-center text-muted mb-4" style="font-size:0.875rem;"> Masuk ke akun Anda</p>

        @if (session('status'))
            <div class="alert alert-success py-2">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope text-primary"></i></span>
                    <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                        value="{{ old('login') }}" placeholder="Email atau NIS" required autofocus>
                    @error('login')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock text-primary"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="cold-flex justify-content-between align-items-center mb-4 12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Ingat saya</label>
                </div>
                <div class="">
                    <small class="text-muted">Belum punya akun? </small>
                    <a href="{{ route('register') }}" class="small fw-semibold">Daftar Sekarang</a>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-primary">Lupa password?</a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100 text-white mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted small mb-0">
                © {{ date('Y') }} Perpustakaan
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
