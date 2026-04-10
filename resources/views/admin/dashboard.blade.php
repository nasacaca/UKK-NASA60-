@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-blue-gradient shadow">
            <i class="bi bi-journal-bookmark-fill stat-icon"></i>
            <p>Total Buku</p>
            <h3>{{ $stats['total_buku'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-teal-gradient shadow">
            <i class="bi bi-people-fill stat-icon"></i>
            <p>Total Siswa</p>
            <h3>{{ $stats['total_siswa'] }}</h3>
        </div>
    </div>  
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-orange-gradient shadow">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <p>Buku Dipinjam</p>
            <h3>{{ $stats['menunggu'] }}</h3>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card bg-red-gradient shadow">
            <i class="bi bi-exclamation-triangle-fill stat-icon"></i>
            <p>Terlambat Kembali</p>
            <h3>{{ $stats['terlambat'] }}</h3>
        </div>
    </div>
</div>


{{-- TABEL PEMINJAMAN TERBARU --}}
{{-- TABEL PEMINJAMAN TERBARU --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock-history me-2 text-primary"></i>Peminjaman Terbaru</span>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Siswa</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans_terbaru as $p)
                    <tr>
                        <td class="fw-semibold text-primary">{{ $p->kode_pinjam }}</td>
                        <td>{{ $p->user->name }}</td>
                        <td>{{ Str::limit($p->buku->judul, 30) }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>
                            {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                            @if($p->status === 'dipinjam' && $p->isTerlambat())
                                <span class="badge bg-danger ms-1">Terlambat</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $p->status }} px-2 py-1">{{ $p->label_status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.peminjaman.show', $p) }}" class="btn btn-xs btn-outline-primary btn-sm py-0 px-2">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada peminjaman</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
