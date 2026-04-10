@extends('layouts.app')
@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')
@section('page-subtitle', 'Temukan dan pinjam buku yang kamu butuhkan')

@section('content')
{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-semibold small">Cari Buku</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Judul, pengarang..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('siswa.katalog') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Grid Buku --}}
<div class="row g-3">
    @forelse($bukus as $buku)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card h-100" style="transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(21,101,192,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            {{-- Cover --}}
            <div style="height:140px;background:linear-gradient(135deg,#1565C0,#42A5F5);border-radius:16px 16px 0 0;display:flex;align-items:center;justify-content:center;position:relative;">
                @if($buku->cover)
                    <img src="{{ asset('storage/'.$buku->cover) }}" style="width:100%;height:100%;object-fit:cover;border-radius:16px 16px 0 0;">
                @else
                    <i class="bi bi-journal-richtext text-white" style="font-size:3rem;opacity:0.7;"></i>
                @endif
                <div style="position:absolute;top:8px;right:8px;">
                    @if($buku->isAvailable())
                        <span class="badge bg-success">Tersedia</span>
                    @else
                        <span class="badge bg-danger">Habis</span>
                    @endif
                </div>
            </div>

            <div class="card-body d-flex flex-column p-3">
                <span class="badge mb-2" style="background:#EEF2FF;color:#3949AB;width:fit-content;">{{ $buku->kategori }}</span>
                <h6 class="fw-bold mb-1" style="font-size:0.875rem;line-height:1.3;">{{ $buku->judul }}</h6>
                <p class="text-muted mb-1" style="font-size:0.78rem;">{{ $buku->pengarang }}</p>
                <p class="text-muted mb-0" style="font-size:0.75rem;">{{ $buku->penerbit }} • {{ $buku->tahun_terbit }}</p>
                <div class="mt-auto pt-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Stok tersedia</small>
                        <strong class="{{ $buku->stok_tersedia > 0 ? 'text-success' : 'text-danger' }}">{{ $buku->stok_tersedia }}/{{ $buku->stok }}</strong>
                    </div>
                    @if($buku->isAvailable())
                        <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#modalPinjam{{ $buku->id }}">
                            <i class="bi bi-book me-1"></i> Pinjam
                        </button>
                    @else
                        <button class="btn btn-secondary btn-sm w-100" disabled>
                            <i class="bi bi-slash-circle me-1"></i> Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Pinjam --}}
    @if($buku->isAvailable())
    <div class="modal fade" id="modalPinjam{{ $buku->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:16px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-book me-2 text-primary"></i>Form Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('siswa.pinjam') }}">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                    <div class="modal-body">
                        <div class="p-3 mb-3" style="background:#EEF2FF;border-radius:12px;">
                            <div class="fw-bold">{{ $buku->judul }}</div>
                            <small class="text-muted">{{ $buku->pengarang }} • {{ $buku->kategori }}</small>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Tanggal Pinjam <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pinjam" class="form-control"
                                    value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold small">Tanggal Kembali <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_kembali_rencana" class="form-control"
                                    value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    max="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Catatan (opsional)</label>
                                <textarea name="catatan" class="form-control" rows="2" placeholder="Keperluan pinjam..."></textarea>
                            </div>
                        </div>
                        <div class="alert alert-info py-2 mt-2 mb-0" style="border-radius:10px;font-size:0.8rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Maksimal peminjaman 30 hari. Denda Rp 1.000/hari jika terlambat.
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i> Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @empty
    <div class="col-12">
        <div class="text-center py-5 text-muted">
            <i class="bi bi-search fs-1 d-block mb-3 opacity-25"></i>
            <h6>Buku tidak ditemukan</h6>
            <p>Coba kata kunci atau kategori lain</p>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-3">{{ $bukus->links() }}</div>
@endsection
