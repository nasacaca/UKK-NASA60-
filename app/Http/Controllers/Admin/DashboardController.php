<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjamans;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buku'      => Buku::count(),
            'total_siswa'     => User::where('role', 'siswa')->count(),
            'dipinjam'        => Peminjamans::where('status', 'dipinjam')->count(),
            'menunggu'        => Peminjamans::where('status', 'menunggu')->count(),
            'dikembalikan'    => Peminjamans::where('status', 'dikembalikan')->count(),
            'terlambat'       => 0,
        ];

        // Hitung terlambat
        $dipinjamList = Peminjamans::where('status', 'dipinjam')->get();
        foreach ($dipinjamList as $p) {
            if ($p->isTerlambat()) $stats['terlambat']++;
        }

        $peminjamans_terbaru = Peminjamans::with(['user', 'buku'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'peminjamans_terbaru'));
    }
}
