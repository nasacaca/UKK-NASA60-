@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')
@section('page-subtitle', $peminjaman->kode_pinjam)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-info-circle me-2 text-primary"></i>Detail Peminjaman</span>
                <span class="badge badge-{{ $peminjaman->status }} px-3 py-2 fs-6">{{ $peminjaman->label_status }}</span>
            </div>
            <div class="card-body">
                {{-- Buku Info --}}
                <div class="d-flex gap-3 mb-4 p-3" style="background:#EEF2FF;border-radius:14px;">
                    <div style="width:56px;height:56px;background:linear-gradient(135deg,#1565C0,#42A5F5);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-journal-bookmark-fill text-white fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6">{{ $peminjaman->buku->judul }}</div>
                        <div class="text-muted small">{{ $peminjaman->buku->pengarang }}</div>
                        <span class="badge mt-1" style="background:#d8e4ff;color:#1565C0;">{{ $peminjaman->buku->kategori }}</span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Kode Peminjaman</small>
                        <strong class="text-primary">{{ $peminjaman->kode_pinjam }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Tanggal Pinjam</small>
                        <strong>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Tanggal Kembali (Rencana)</small>
                        <strong>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Tanggal Kembali (Aktual)</small>
                        <strong>{{ $peminjaman->tanggal_kembali_aktual ? $peminjaman->tanggal_kembali_aktual->format('d F Y') : '-' }}</strong>
                    </div>

                    @if($peminjaman->catatan)
                    <div class="col-12">
                        <small class="text-muted d-block">Catatan</small>
                        <p class="mb-0">{{ $peminjaman->catatan }}</p>
                    </div>
                    @endif

                    @if($peminjaman->alasan_tolak)
                    <div class="col-12">
                        <small class="text-muted d-block">Alasan Penolakan</small>
                        <div class="alert alert-danger py-2 mb-0">{{ $peminjaman->alasan_tolak }}</div>
                    </div>
                    @endif

                    @if($peminjaman->denda > 0)
                    <div class="col-12">
                        <div class="alert alert-danger d-flex align-items-center mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>Denda: Rp {{ number_format($peminjaman->denda) }}</strong>
                                <small class="d-block">Harap segera dibayar ke petugas perpustakaan.</small>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($peminjaman->status === 'dipinjam' && $peminjaman->isTerlambat())
                    <div class="col-12">
                        <div class="alert alert-warning d-flex align-items-center mb-0">
                            <i class="bi bi-clock-fill me-2"></i>
                            <div>
                                <strong>Buku terlambat dikembalikan!</strong>
                                <small class="d-block">Estimasi denda: Rp {{ number_format($peminjaman->hitungDenda()) }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            @if($peminjaman->status === 'menunggu')
            <form method="POST" action="{{ route('siswa.peminjaman.batalkan', $peminjaman) }}"
                onsubmit="return confirm('Yakin batalkan peminjaman ini?')">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-1"></i> Batalkan
                </button>
            </form>
            @endif
            <a href="{{ route('siswa.peminjaman.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
