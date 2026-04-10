@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
{{-- Greeting Card --}}
<div class="card mb-4" style="background: linear-gradient(135deg,#1565C0,#1976D2);border:none;border-radius:20px;">
    <div class="card-body p-4 text-white d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}! 👋</h5>
            <p class="mb-0 opacity-75">
                @if(auth()->user()->kelas) Kelas {{ auth()->user()->kelas }} • @endif
                @if(auth()->user()->nis) NIS: {{ auth()->user()->nis }} • @endif
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <a href="{{ route('siswa.katalog') }}" class="btn btn-light fw-semibold px-4" style="border-radius:12px;color:#1565C0;">
            <i class="bi bi-search me-2"></i> Cari Buku
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-blue-gradient shadow">
            <i class="bi bi-journal-text stat-icon"></i>
            <p>Total Pinjam</p>
            <h3>{{ $stats['total_pinjam'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-purple-gradient shadow">
            <i class="bi bi-book-fill stat-icon"></i>
            <p>Sedang Dipinjam</p>
            <h3>{{ $stats['sedang_pinjam'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-orange-gradient shadow">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <p>Menunggu</p>
            <h3>{{ $stats['menunggu'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-teal-gradient shadow">
            <i class="bi bi-check-circle-fill stat-icon"></i>
            <p>Selesai</p>
            <h3>{{ $stats['selesai'] }}</h3>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Peminjaman Aktif --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Peminjaman Aktif</span>
                <a href="{{ route('siswa.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($peminjamans_aktif as $p)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div style="width:44px;height:44px;background:#EEF2FF;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-journal-bookmark-fill text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ Str::limit($p->buku->judul, 35) }}</div>
                        <small class="text-muted">
                            Kembali: {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                            @if($p->status === 'dipinjam' && $p->isTerlambat())
                                <span class="badge bg-danger ms-1">Terlambat!</span>
                            @endif
                        </small>
                    </div>
                    <span class="badge badge-{{ $p->status }} px-2 py-1">{{ $p->label_status }}</span>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-25"></i>
                    Belum ada peminjaman aktif
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Buku Tersedia --}}
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-collection-fill me-2 text-primary"></i>Buku Terbaru</span>
                <a href="{{ route('siswa.katalog') }}" class="btn btn-sm btn-outline-primary">Katalog</a>
            </div>
            <div class="card-body p-0">
                @foreach($buku_terbaru as $buku)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <div style="width:44px;height:44px;background:linear-gradient(135deg,#1565C0,#42A5F5);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-book-fill text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:0.85rem;">{{ Str::limit($buku->judul, 30) }}</div>
                        <small class="text-muted">{{ $buku->pengarang }}</small>
                    </div>
                    <span class="badge bg-success">{{ $buku->stok_tersedia }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
