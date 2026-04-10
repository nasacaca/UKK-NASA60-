<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'siswa'])->default('siswa')->after('email');
            $table->string('nis')->nullable()->after('role'); // Nomor Induk Siswa
            $table->string('kelas')->nullable()->after('nis');
            $table->string('alamat')->nullable()->after('kelas');
            $table->string('telepon')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nis', 'kelas', 'alamat', 'telepon']);
        });
    }
};
