@extends('layouts.app')
@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')
@section('page-subtitle', 'Perbarui data buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2 text-primary"></i>Form Edit Buku
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.buku.update', $buku) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kode Buku <span class="text-danger">*</span></label>
                            <input type="text" name="kode_buku" class="form-control @error('kode_buku') is-invalid @enderror"
                                value="{{ old('kode_buku', $buku->kode_buku) }}">
                            @error('kode_buku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul', $buku->judul) }}">
                            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pengarang <span class="text-danger">*</span></label>
                            <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror"
                                value="{{ old('pengarang', $buku->pengarang) }}">
                            @error('pengarang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Penerbit <span class="text-danger">*</span></label>
                            <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
                                value="{{ old('penerbit', $buku->penerbit) }}">
                            @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tahun Terbit <span class="text-danger">*</span></label>
                            <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
                                value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900" max="{{ date('Y') }}">
                            @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="kategori" list="kategori-list"
                                class="form-control @error('kategori') is-invalid @enderror"
                                value="{{ old('kategori', $buku->kategori) }}">
                            <datalist id="kategori-list">
                                @foreach($kategoris as $k)
                                    <option value="{{ $k }}">
                                @endforeach
                            </datalist>
                            @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stok Total <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                value="{{ old('stok', $buku->stok) }}" min="1">
                            <small class="text-muted">Tersedia saat ini: {{ $buku->stok_tersedia }}</small>
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" name="isbn" class="form-control"
                                value="{{ old('isbn', $buku->isbn) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cover Buku</label>
                            @if($buku->cover)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$buku->cover) }}" height="60" class="rounded border">
                                    <small class="text-muted ms-2">Cover saat ini</small>
                                </div>
                            @endif
                            <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror" accept="image/*">
                            @error('cover')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Perbarui
                        </button>
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
