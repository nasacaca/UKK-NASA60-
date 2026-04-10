<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjamans;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->peminjamans()->with('buku');
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $peminjamans = $query->latest()->paginate(10)->withQueryString();
        return view('siswa.peminjaman.index', compact('peminjamans'));
    }

    public function katalog(Request $request)
    {
        $query = Buku::query();
        if ($request->search) {
            $query->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('pengarang', 'like', '%'.$request->search.'%');
        }
        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }
        $bukus = $query->latest()->paginate(12)->withQueryString();
        $kategoris = Buku::distinct()->pluck('kategori');
        return view('siswa.katalog', compact('bukus', 'kategoris'));
    }

    public function pinjam(Request $request)
    {
        $request->validate([
            'buku_id'                 => 'required|exists:bukus,id',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'catatan'                 => 'nullable|string|max:500',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if (!$buku->isAvailable()) {
            return back()->with('error', 'Stok buku tidak tersedia!');
        }

        // Cek apakah sudah meminjam buku yang sama
        $sudahPinjam = auth()->user()->peminjamans()
            ->where('buku_id', $request->buku_id)
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->exists(); 

        if ($sudahPinjam) {
            return back()->with('error', 'Anda sudah meminjam buku ini!');
        }

        // Cek maksimal 3 buku aktif
        $aktif = auth()->user()->peminjamans()
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->count();

        if ($aktif >= 3) {
            return back()->with('error', 'Maksimal 3 peminjaman aktif!');
        }

        $kode = 'PJM-' . date('Ymd') . '-' . str_pad(Peminjamans::count() + 1, 4, '0', STR_PAD_LEFT);

        Peminjamans::create([
            'kode_pinjam'             => $kode,
            'user_id'                 => auth()->id(),
            'buku_id'                 => $request->buku_id,
            'tanggal_pinjam'          => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'catatan'                 => $request->catatan,
            'status'                  => 'menunggu',
        ]);

        return redirect()->route('siswa.peminjaman.index')->with('success', 'Permintaan peminjaman berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function show(Peminjamans $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }
        return view('siswa.peminjaman.show', compact('peminjaman'));
    }

    public function batalkan(Peminjamans $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id() || $peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Tidak bisa membatalkan peminjaman ini!');
        }
        $peminjaman->update(['status' => 'ditolak', 'alasan_tolak' => 'Dibatalkan oleh siswa.']);
        return back()->with('success', 'Peminjaman berhasil dibatalkan!');
    }
}
