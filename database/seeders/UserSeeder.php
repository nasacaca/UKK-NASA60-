<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun Admin
        User::create([
            'name'     => 'Administrator Perpustakaan',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Password untuk login
            'role'     => 'admin',
            'telepon'  => '081234567890',
        ]);

        // Membuat Akun Siswa (Rivan)
        User::create([
            'name'     => 'Rivan Hermawan',
            'email'    => 'rivan@gmail.com',
            'password' => Hash::make('rivanjelek'),
            'role'     => 'siswa',
            'nis'      => '12345678',
            'kelas'    => 'XII RPL',
            'alamat'   => 'Kota Tangerang',
            'telepon'  => '089876543210',
        ]);
    }
}