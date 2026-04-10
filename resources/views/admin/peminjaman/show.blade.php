@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')
@section('page-subtitle', $peminjaman->kode_pinjam)

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Peminjaman</span>
                <span class="badge badge-{{ $peminjaman->status }} px-3 py-2 fs-6">{{ $peminjaman->label_status }}</span>
            </div>
            <div class="card-body">
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
                            <strong>Denda: Rp {{ number_format($peminjaman->denda) }}</strong>
                            <small class="ms-2">(Rp 1.000/hari keterlambatan)</small>
                        </div>
                    </div>
                    @endif
                    @if($peminjaman->status === 'dipinjam' && $peminjaman->isTerlambat())
                    <div class="col-12">
                        <div class="alert alert-warning d-flex align-items-center mb-0">
                            <i class="bi bi-clock-fill me-2"></i>
                            <strong>Estimasi Denda Saat Ini: Rp {{ number_format($peminjaman->hitungDenda()) }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex gap-2 flex-wrap">
            @if($peminjaman->status === 'menunggu')
                <form method="POST" action="{{ route('admin.peminjaman.approve', $peminjaman) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Setujui Peminjaman
                    </button>
                </form>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                    <i class="bi bi-x-circle me-1"></i> Tolak Peminjaman
                </button>
            @endif
            @if($peminjaman->status === 'dipinjam')
                <form method="POST" action="{{ route('admin.peminjaman.kembalikan', $peminjaman) }}"
                    onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                    @csrf
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-return-left me-1"></i> Proses Pengembalian
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Info Siswa --}}
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-fill me-2 text-primary"></i>Data Siswa</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#EEF2FF;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-fill text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold">{{ $peminjaman->user->name }}</div>
                        <small class="text-muted">{{ $peminjaman->user->email }}</small>
                    </div>
                </div>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:40%">NIS</td><td class="fw-semibold">{{ $peminjaman->user->nis ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kelas</td><td class="fw-semibold">{{ $peminjaman->user->kelas ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Telepon</td><td class="fw-semibold">{{ $peminjaman->user->telepon ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        {{-- Info Buku --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Data Buku</div>
            <div class="card-body">
                @if($peminjaman->buku->cover)
                    <img src="{{ asset('storage/'.$peminjaman->buku->cover) }}" class="img-fluid rounded mb-3" style="max-height:120px;object-fit:cover;">
                @endif
                <div class="fw-bold fs-6 mb-1">{{ $peminjaman->buku->judul }}</div>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted" style="width:40%">Kode</td><td class="fw-semibold text-primary">{{ $peminjaman->buku->kode_buku }}</td></tr>
                    <tr><td class="text-muted">Pengarang</td><td class="fw-semibold">{{ $peminjaman->buku->pengarang }}</td></tr>
                    <tr><td class="text-muted">Penerbit</td><td class="fw-semibold">{{ $peminjaman->buku->penerbit }}</td></tr>
                    <tr><td class="text-muted">Kategori</td><td><span class="badge" style="background:#EEF2FF;color:#3949AB;">{{ $peminjaman->buku->kategori }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
@if($peminjaman->status === 'menunggu')
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.peminjaman.tolak', $peminjaman) }}">
                @csrf
                <div class="modal-body">
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Masukkan alasan..."></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
