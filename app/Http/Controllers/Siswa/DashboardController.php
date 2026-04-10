<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjamans;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats = [
            'total_pinjam'  => $user->peminjamans()->count(),
            'sedang_pinjam' => $user->peminjamans()->where('status', 'dipinjam')->count(),
            'menunggu'      => $user->peminjamans()->where('status', 'menunggu')->count(),
            'selesai'       => $user->peminjamans()->where('status', 'dikembalikan')->count(),
        ];

        $peminjamans_aktif = $user->peminjamans()
            ->with('buku')
            ->whereIn('status', ['menunggu', 'dipinjam'])
            ->latest()
            ->take(5)
            ->get();

        $buku_terbaru = Buku::where('stok_tersedia', '>', 0)->latest()->take(6)->get();

        return view('siswa.dashboard', compact('stats', 'peminjamans_aktif', 'buku_terbaru'));
    }
}
