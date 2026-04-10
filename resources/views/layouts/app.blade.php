<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan') - SMA Nusantara</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bs-primary: #1565C0;
            --bs-primary-rgb: 21, 101, 192;
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --sidebar-bg: #0D47A1;
            --sidebar-accent: #1565C0;
            --sidebar-text: rgba(255, 255, 255, 0.85);
            --sidebar-text-active: #ffffff;
            --topbar-bg: #ffffff;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #F0F4FF;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1.05rem;
            margin: 0;
        }

        .sidebar-brand p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            margin: 2px 0 0;
        }

        .sidebar-section {
            padding: 16px 12px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
        }

        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            padding: 10px 16px;
            border-radius: 10px;
            margin: 2px 8px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 20px;
        }

        /* ===== TOPBAR ===== */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--topbar-bg);
            border-bottom: 1px solid #e3eaff;
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 24px;
            justify-content: space-between;
            box-shadow: 0 2px 12px rgba(21, 101, 192, 0.08);
        }

        /* ===== MAIN CONTENT ===== */
        #main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 28px;
            min-height: calc(100vh - var(--topbar-height));
        }

        /* ===== CARDS ===== */
        .card {
            border: 1px solid #dde8ff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(21, 101, 192, 0.06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #dde8ff;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 20px;
            font-weight: 600;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            border-radius: 16px;
            border: none;
            padding: 22px;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.25;
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
        }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card p {
            font-size: 0.8rem;
            opacity: 0.85;
            margin: 0;
        }

        .bg-blue-gradient {
            background: linear-gradient(135deg, #1565C0, #1976D2);
        }

        .bg-teal-gradient {
            background: linear-gradient(135deg, #00695C, #00897B);
        }

        .bg-orange-gradient {
            background: linear-gradient(135deg, #E65100, #F57C00);
        }

        .bg-purple-gradient {
            background: linear-gradient(135deg, #4527A0, #5E35B1);
        }

        .bg-red-gradient {
            background: linear-gradient(135deg, #B71C1C, #C62828);
        }

        .bg-cyan-gradient {
            background: linear-gradient(135deg, #006064, #00838F);
        }

        /* ===== BADGE STATUS ===== */
        .badge-menunggu {
            background: #FFF3CD;
            color: #856404;
        }

        .badge-dipinjam {
            background: #CCE5FF;
            color: #004085;
        }

        .badge-dikembalikan {
            background: #D4EDDA;
            color: #155724;
        }

        .badge-ditolak {
            background: #F8D7DA;
            color: #721C24;
        }

        /* ===== TABLES ===== */
        .table {
            border-color: #e3eaff;
        }

        .table thead th {
            background: #EEF2FF;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #3949AB;
            border: none;
        }

        .table tbody tr:hover {
            background: #F5F8FF;
        }

        /* ===== FORMS ===== */
        .form-control,
        .form-select {
            border-color: #c5d3f0;
            border-radius: 10px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1565C0;
            box-shadow: 0 0 0 0.25rem rgba(21, 101, 192, 0.15);
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            background: #1565C0;
            border-color: #1565C0;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #0D47A1;
            border-color: #0D47A1;
        }

        .btn-outline-primary {
            color: #1565C0;
            border-color: #1565C0;
            font-weight: 600;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            margin-bottom: 24px;
        }

        .page-header h4 {
            font-weight: 700;
            color: #0D47A1;
            margin: 0;
        }

        .page-header p {
            color: #6c757d;
            margin: 4px 0 0;
            font-size: 0.875rem;
        }

        /* ALERT */
        .alert {
            border-radius: 12px;
            border: none;
        }

        .alert-success {
            background: #D4EDDA;
            color: #155724;
        }

        .alert-danger {
            background: #F8D7DA;
            color: #721C24;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            #topbar {
                left: 0;
            }

            #main-content {
                margin-left: 0;
                padding: 16px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- SIDEBAR -->
    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center gap-2 mb-1">
                <div>
                    <img src="{{ asset('image/logo.png') }}" alt="Judul Buku"
                        style="width: 50px; height: 50px; object-fit: cover;" class="rounded shadow-sm">
                </div>
                <div>
                    <h5>SmartRead</h5>
                    <p>Perpustakaan</p>
                </div>
            </div>
        </div>
        @auth
            <ul class="sidebar-nav list-unstyled mt-2">
                @if (auth()->user()->isAdmin())
                    <li><span class="sidebar-section">Menu Utama</span></li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <li><span class="sidebar-section">Manajemen</span></li>
                    <li>
                        <a href="{{ route('admin.buku.index') }}"
                            class="nav-link {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                            <i class="bi bi-journal-bookmark-fill"></i> Data Buku
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.siswa.index') }}"
                            class="nav-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i> Data Siswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.peminjaman.index') }}"
                            class="nav-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-left-right"></i> Peminjaman
                        </a>
                    </li>
                @else
                    <li><span class="sidebar-section">Menu</span></li>
                    <li>
                        <a href="{{ route('siswa.dashboard') }}"
                            class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-fill"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.katalog') }}"
                            class="nav-link {{ request()->routeIs('siswa.katalog') ? 'active' : '' }}">
                            <i class="bi bi-search"></i> Katalog Buku
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('siswa.peminjaman.index') }}"
                            class="nav-link {{ request()->routeIs('siswa.peminjaman.*') ? 'active' : '' }}">
                            <i class="bi bi-clock-history"></i> Peminjaman Saya
                        </a>
                    </li>
                @endif
            </ul>

            <div class="mt-auto p-3"
                style="position:absolute;bottom:0;left:0;right:0;border-top:1px solid rgba(255,255,255,0.1);">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div
                        style="width:36px;height:36px;background:rgba(255,255,255,0.2);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-fill text-white"></i>
                    </div>
                    <div>
                        <div style="color:#fff;font-size:0.8rem;font-weight:600;">{{ auth()->user()->name }}</div>
                        <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">{{ ucfirst(auth()->user()->role) }}
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100"
                        style="background:rgba(255,255,255,0.15);color:#fff;border:none;border-radius:8px;">
                        <i class="bi bi-box-arrow-left me-1"></i> Logout
                    </button>
                </form>
            </div>
        @endauth
    </nav>

    <!-- TOPBAR -->
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')"
                style="border:none;background:none;">
                <i class="bi bi-list fs-4 text-primary"></i>
            </button>
            <div>
                <div style="font-weight:700;color:#0D47A1;font-size:0.95rem;">@yield('page-title', 'Dashboard')</div>
                <div style="font-size:0.72rem;color:#6c757d;">@yield('page-subtitle', '')</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge"
                style="background:#EEF2FF;color:#1565C0;font-size:0.75rem;padding:6px 12px;border-radius:20px;">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div id="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
