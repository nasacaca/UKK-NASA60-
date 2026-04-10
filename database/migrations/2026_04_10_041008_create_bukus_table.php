<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->year('tahun_terbit');
            $table->string('kategori');
            $table->integer('stok');
            $table->integer('stok_tersedia');
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->string('isbn')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
