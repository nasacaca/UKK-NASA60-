<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Siswa;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// Tambahkan import RegisterController di sini
use App\Http\Controllers\Auth\RegisteredUserController; 

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('siswa.dashboard');
    }
    return redirect()->route('login');
});

// --- RUTE AUTH (Bisa diakses publik) ---
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Tambahkan rute Register di sini agar tidak error RouteNotFound
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// --- ADMIN ROUTES ---
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('buku', Admin\BukuController::class);
    Route::resource('siswa', Admin\SiswaController::class);

    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [Admin\PeminjamanController::class, 'index'])->name('index');
        Route::get('/{peminjaman}', [Admin\PeminjamanController::class, 'show'])->name('show');
        Route::post('/{peminjaman}/approve', [Admin\PeminjamanController::class, 'approve'])->name('approve');
        Route::post('/{peminjaman}/tolak', [Admin\PeminjamanController::class, 'tolak'])->name('tolak');
        Route::post('/{peminjaman}/kembalikan', [Admin\PeminjamanController::class, 'kembalikan'])->name('kembalikan');
    });
});

// --- SISWA ROUTES ---
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [Siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/katalog', [Siswa\PeminjamanController::class, 'katalog'])->name('katalog');
    Route::post('/pinjam', [Siswa\PeminjamanController::class, 'pinjam'])->name('pinjam');
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [Siswa\PeminjamanController::class, 'index'])->name('index');
        Route::get('/{peminjaman}', [Siswa\PeminjamanController::class, 'show'])->name('show');
        Route::post('/{peminjaman}/batalkan', [Siswa\PeminjamanController::class, 'batalkan'])->name('batalkan');
    });
});