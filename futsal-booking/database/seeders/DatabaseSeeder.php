<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lapangan;
use App\Models\SkemaHarga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Akun Admin ───────────────────────────────────────────────
        User::create([
            'name'     => 'Admin FutsalPro',
            'email'    => 'admin@futsal.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ─── Akun Customer (untuk testing) ────────────────────────────
        User::create([
            'name'     => 'Aliansyah',
            'email'    => 'ali@futsal.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // ─── Data Lapangan ────────────────────────────────────────────
        Lapangan::create(['nama_lapangan' => 'Lapangan Sintetis A']);
        Lapangan::create(['nama_lapangan' => 'Lapangan Sintetis B']);
        Lapangan::create(['nama_lapangan' => 'Lapangan Matras C']);

        // ─── Skema Harga (Jam Siang & Malam) ─────────────────────────
        SkemaHarga::create([
            'jam_mulai'   => '08:00',
            'jam_selesai' => '09:00',
            'harga'       => 100000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '09:00',
            'jam_selesai' => '10:00',
            'harga'       => 100000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '10:00',
            'jam_selesai' => '11:00',
            'harga'       => 100000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '11:00',
            'jam_selesai' => '12:00',
            'harga'       => 100000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '13:00',
            'jam_selesai' => '14:00',
            'harga'       => 120000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '14:00',
            'jam_selesai' => '15:00',
            'harga'       => 120000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '15:00',
            'jam_selesai' => '16:00',
            'harga'       => 150000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '16:00',
            'jam_selesai' => '17:00',
            'harga'       => 150000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '18:00',
            'jam_selesai' => '19:00',
            'harga'       => 200000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '19:00',
            'jam_selesai' => '20:00',
            'harga'       => 200000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '20:00',
            'jam_selesai' => '21:00',
            'harga'       => 200000,
        ]);
        SkemaHarga::create([
            'jam_mulai'   => '21:00',
            'jam_selesai' => '22:00',
            'harga'       => 200000,
        ]);
    }
}
