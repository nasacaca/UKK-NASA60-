<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjamans extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pinjam',
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'denda',
        'catatan',
        'alasan_tolak',
        'approved_by',
    ];

    protected $casts = [
        'tanggal_pinjam'           => 'date',
        'tanggal_kembali_rencana'  => 'date',
        'tanggal_kembali_aktual'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hitungDenda(): int
    {
        if ($this->status === 'dikembalikan' && $this->tanggal_kembali_aktual) {
            $terlambat = $this->tanggal_kembali_aktual->diffInDays($this->tanggal_kembali_rencana, false);
            return $terlambat < 0 ? abs($terlambat) * 1000 : 0;
        }
        if ($this->status === 'dipinjam') {
            $terlambat = Carbon::today()->diffInDays($this->tanggal_kembali_rencana, false);
            return $terlambat < 0 ? abs($terlambat) * 1000 : 0;
        }
        return 0;
    }

    public function isTerlambat(): bool
    {
        return $this->hitungDenda() > 0;
    }

    public function getBadgeStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'warning',
            'dipinjam'     => 'primary',
            'dikembalikan' => 'success',
            'ditolak'      => 'danger',
            default        => 'secondary',
        };
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'Menunggu',
            'dipinjam'     => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'ditolak'      => 'Ditolak',
            default        => 'Unknown',
        };
    }
}
