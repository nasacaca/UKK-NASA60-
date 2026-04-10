<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan SMA Nusantara</title>
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
            /* Warna gradasi disamakan persis dengan Login */
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1976D2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 20px 0;
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

        .btn-register {
            background: linear-gradient(135deg, #1565C0, #1976D2);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 700;
            letter-spacing: 0.03em;
            box-shadow: 0 4px 15px rgba(21, 101, 192, 0.3);
            transition: all 0.2s;
            color: white;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(21, 101, 192, 0.4);
            color: white;
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
        <h4 class="text-center fw-bold mb-1" style="color:#0D47A1;">Daftar Akun</h4>
        <p class="text-center text-muted mb-4" style="font-size:0.875rem;">Buat akun untuk mulai meminjam buku</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person text-primary"></i></span>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Nama Lengkap" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope text-primary"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="Email" required>
                    @error('email')
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

            <div class="mb-4">
                <label class="form-label fw-semibold small">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-check text-primary"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-register w-100 mb-3">
                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
            </button>
        </form>

        <div class="text-center">
            <p class="text-muted small mb-0">
                Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none"
                    style="color:#0D47A1;">Masuk</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
