<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lapangan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed akun admin
        User::create([
            'name'     => 'Admin JuneFutsal',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Seed data lapangan JuneFutsal
        Lapangan::create([
            'nama_lapangan'  => 'Lapangan A',
            'jenis_lapangan' => 'Sintetis',
            'harga_pagi'     => 80000,
            'harga_malam'    => 120000,
        ]);

        Lapangan::create([
            'nama_lapangan'  => 'Lapangan B',
            'jenis_lapangan' => 'Vinyl',
            'harga_pagi'     => 100000,
            'harga_malam'    => 135000,
        ]);
    }
}
