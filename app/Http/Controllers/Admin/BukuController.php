<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('pengarang', 'like', '%' . $request->search . '%')
                ->orWhere('kode_buku', 'like', '%' . $request->search . '%');
        }
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        $bukus = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Buku::distinct()->pluck('kategori');
        return view('admin.buku.index', compact('bukus', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Buku::distinct()->pluck('kategori');
        return view('admin.buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku'    => 'required|unique:bukus',
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'kategori'     => 'required|string|max:100',
            'stok'         => 'required|integer|min:1',
            'isbn'         => 'nullable|string|max:50',
            'deskripsi'    => 'nullable|string',
            'cover'        => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['stok_tersedia'] = $request->stok;

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit(Buku $buku)
    {
        $kategoris = Buku::distinct()->pluck('kategori');
        return view('admin.buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode_buku'    => 'required|unique:bukus,kode_buku,' . $buku->id,
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|digits:4',
            'kategori'     => 'required|string|max:100',
            'stok'         => 'required|integer|min:1',
            'isbn'         => 'nullable|string|max:50',
            'deskripsi'    => 'nullable|string',
            'cover'        => 'nullable|image|max:2048',
        ]);

        $data = $request->except('cover');
        // Sesuaikan stok_tersedia
        $selisih = $request->stok - $buku->stok;
        $data['stok_tersedia'] = max(0, $buku->stok_tersedia + $selisih);

        if ($request->hasFile('cover')) {
            if ($buku->cover) Storage::disk('public')->delete($buku->cover);
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->peminjamans()->whereIn('status', ['menunggu', 'dipinjam'])->exists()) {
            return back()->with('error', 'Buku masih dipinjam, tidak bisa dihapus!');
        }
        if ($buku->cover) Storage::disk('public')->delete($buku->cover);
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
