@extends('layouts.app')
@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')
@section('page-subtitle', 'Riwayat dan status peminjaman buku')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-clock-history me-2 text-primary"></i>Daftar Peminjaman</span>
        <a href="{{ route('siswa.katalog') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Pinjam Buku Baru
        </a>
    </div>
    <div class="card-body border-bottom pb-3">
        <form method="GET">
            <div class="d-flex gap-2 flex-wrap">
                @foreach([''=>'Semua', 'menunggu'=>'Menunggu', 'dipinjam'=>'Dipinjam', 'dikembalikan'=>'Dikembalikan', 'ditolak'=>'Ditolak'] as $val => $label)
                <a href="?status={{ $val }}"
                    class="btn btn-sm {{ request('status') == $val ? 'btn-primary' : 'btn-outline-secondary' }}"
                    style="border-radius:20px;">{{ $label }}</a>
                @endforeach
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        @forelse($peminjamans as $p)
        <div class="d-flex align-items-start gap-3 p-3 border-bottom">
            <div style="width:48px;height:48px;background:linear-gradient(135deg,#1565C0,#42A5F5);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-journal-bookmark-fill text-white fs-5"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <div class="fw-bold">{{ $p->buku->judul }}</div>
                        <small class="text-muted">{{ $p->buku->pengarang }} • {{ $p->buku->kategori }}</small>
                    </div>
                    <span class="badge badge-{{ $p->status }} px-2 py-1">{{ $p->label_status }}</span>
                </div>
                <div class="mt-2 d-flex flex-wrap gap-3">
                    <small class="text-muted"><i class="bi bi-calendar me-1"></i>Pinjam: <strong>{{ $p->tanggal_pinjam->format('d/m/Y') }}</strong></small>
                    <small class="text-muted"><i class="bi bi-calendar-check me-1"></i>Kembali: <strong>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</strong></small>
                    @if($p->denda > 0)
                        <small class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Denda: <strong>Rp {{ number_format($p->denda) }}</strong></small>
                    @elseif($p->status === 'dipinjam' && $p->isTerlambat())
                        <small class="text-warning"><i class="bi bi-clock me-1"></i>Estimasi Denda: <strong>Rp {{ number_format($p->hitungDenda()) }}</strong></small>
                    @endif
                </div>
                @if($p->alasan_tolak)
                <div class="mt-1">
                    <small class="text-danger"><i class="bi bi-x-circle me-1"></i>{{ $p->alasan_tolak }}</small>
                </div>
                @endif
            </div>
            <div class="d-flex gap-1 flex-shrink-0">
                <a href="{{ route('siswa.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                    <i class="bi bi-eye"></i>
                </a>
                @if($p->status === 'menunggu')
                <form method="POST" action="{{ route('siswa.peminjaman.batalkan', $p) }}"
                    onsubmit="return confirm('Yakin batalkan peminjaman ini?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Batalkan">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-25"></i>
            <h6>Belum ada peminjaman</h6>
            <a href="{{ route('siswa.katalog') }}" class="btn btn-primary btn-sm mt-2">Cari Buku Sekarang</a>
        </div>
        @endforelse
    </div>
    @if($peminjamans->hasPages())
    <div class="card-body pt-2">{{ $peminjamans->links() }}</div>
    @endif
</div>
@endsection
