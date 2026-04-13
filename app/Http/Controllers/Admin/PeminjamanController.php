<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjamans;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjamans::with(['user', 'buku']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                ->orWhereHas('buku', fn($q) => $q->where('judul', 'like', '%' . $request->search . '%'))
                ->orWhere('kode_pinjam', 'like', '%' . $request->search . '%');
        }

        $peminjamans = $query->latest()->paginate(15)->withQueryString();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function show(Peminjamans $peminjaman)
    {
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function approve(Peminjamans $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return back()->with('error', 'Peminjaman tidak dalam status menunggu!');
        }

        if (!$peminjaman->buku->isAvailable()) {
            return back()->with('error', 'Stok buku tidak tersedia!');
        }

        $peminjaman->update([
            'status'      => 'dipinjam',
            'approved_by' => auth()->id(),
        ]);

        $peminjaman->buku->decrement('stok_tersedia');

        return back()->with('success', 'Peminjaman disetujui!');
    }

    public function tolak(Request $request, Peminjamans $peminjaman)
    {
        $request->validate(['alasan_tolak' => 'required|string']);

        $peminjaman->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $request->alasan_tolak,
        ]);

        return back()->with('success', 'Peminjaman ditolak!');
    }

    public function kembalikan(Request $request, Peminjamans $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku belum dipinjam!');
        }

        $tanggal_kembali = Carbon::today();
        $denda = 0;

        if ($tanggal_kembali->gt($peminjaman->tanggal_kembali_rencana)) {
            $terlambat = $tanggal_kembali->diffInDays($peminjaman->tanggal_kembali_rencana);
            $denda = $terlambat * 10000; // Rp 1.000 per hari
        }

        $peminjaman->update([
            'status'                 => 'dikembalikan',
            'tanggal_kembali_aktual' => $tanggal_kembali,
            'denda'                  => $denda,
        ]);

        $peminjaman->buku->increment('stok_tersedia');

        return back()->with('success', 'Buku berhasil dikembalikan! Denda: Rp ' . number_format($denda));
    }
}
