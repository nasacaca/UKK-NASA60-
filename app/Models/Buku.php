<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_buku', 'judul', 'pengarang', 'penerbit', 'tahun_terbit',
        'kategori', 'stok', 'stok_tersedia', 'deskripsi', 'cover', 'isbn',
    ];

    public function peminjamans()
    {
        return $this->hasMany(Peminjamans::class);
    }

    public function isAvailable(): bool
    {
        return $this->stok_tersedia > 0;
    }
}
