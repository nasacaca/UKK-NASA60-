@extends('layouts.app')
@section('title', 'Data Peminjaman')
@section('page-title', 'Data Peminjaman')
@section('page-subtitle', 'Kelola semua peminjaman buku')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-arrow-left-right me-2 text-primary"></i>Daftar Peminjaman
    </div>
    <div class="card-body border-bottom pb-3">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Cari kode, siswa, buku..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu"     {{ request('status')=='menunggu'     ? 'selected':'' }}>Menunggu</option>
                    <option value="dipinjam"     {{ request('status')=='dipinjam'     ? 'selected':'' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ request('status')=='dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
                    <option value="ditolak"      {{ request('status')=='ditolak'      ? 'selected':'' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-1">
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
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
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $p)
                    <tr>
                        <td class="fw-semibold text-primary">{{ $p->kode_pinjam }}</td>
                        <td>
                            <div class="fw-semibold">{{ $p->user->name }}</div>
                            <small class="text-muted">{{ $p->user->nis ?? '-' }}</small>
                        </td>
                        <td>{{ Str::limit($p->buku->judul, 28) }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>
                            {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                            @if($p->status === 'dipinjam' && $p->isTerlambat())
                                <br><span class="badge bg-danger">Terlambat!</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $p->status }} px-2 py-1">{{ $p->label_status }}</span>
                        </td>
                        <td>
                            @if($p->denda > 0)
                                <span class="text-danger fw-semibold">Rp {{ number_format($p->denda) }}</span>
                            @elseif($p->status === 'dipinjam' && $p->isTerlambat())
                                <span class="text-warning fw-semibold">Rp {{ number_format($p->hitungDenda()) }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>   
                    </tr>

                    {{-- Modal Tolak --}}
                    @if($p->status === 'menunggu')
                    <div class="modal fade" id="modalTolak{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content" style="border-radius:16px;">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Peminjaman</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.peminjaman.tolak', $p) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted">Buku: <strong>{{ $p->buku->judul }}</strong></p>
                                        <p class="text-muted">Siswa: <strong>{{ $p->user->name }}</strong></p>
                                        <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                        <textarea name="alasan_tolak" class="form-control" rows="3" required
                                            placeholder="Masukkan alasan penolakan..."></textarea>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data peminjaman</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $peminjamans->links() }}</div>
    </div>
</div>
@endsection
