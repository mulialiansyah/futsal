<?php

namespace Database\Seeders;

use App\Models\HariLibur;
use App\Models\Lapangan;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // ===== LAPANGAN (9 total: 5 standar, 4 internasional) =====
        $lapangans = [
            // Standar: 2 sintetis + 3 vinyl | 2 outdoor + 3 indoor
            ['nama_lapangan' => 'Standar A1', 'kategori' => 'standar', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'outdoor'],
            ['nama_lapangan' => 'Standar A2', 'kategori' => 'standar', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'outdoor'],
            ['nama_lapangan' => 'Standar A3', 'kategori' => 'standar', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Standar A4', 'kategori' => 'standar', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Standar A5', 'kategori' => 'standar', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],

            // Internasional: 2 sintetis + 2 vinyl | 1 outdoor + 3 indoor
            ['nama_lapangan' => 'Internasional B1', 'kategori' => 'internasional', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'outdoor'],
            ['nama_lapangan' => 'Internasional B2', 'kategori' => 'internasional', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Internasional B3', 'kategori' => 'internasional', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Internasional B4', 'kategori' => 'internasional', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
        ];

        foreach ($lapangans as $data) {
            Lapangan::create($data);
        }

        // ===== TARIF (8 baris: 2 kategori x 2 tipe hari x 2 window jam) =====
        $tarifs = [
            ['kategori' => 'standar',       'tipe_hari' => 'weekday', 'jam_mulai' => '08:00', 'jam_selesai' => '15:00', 'harga' => 60000],
            ['kategori' => 'standar',       'tipe_hari' => 'weekday', 'jam_mulai' => '15:00', 'jam_selesai' => '21:00', 'harga' => 100000],
            ['kategori' => 'internasional', 'tipe_hari' => 'weekday', 'jam_mulai' => '08:00', 'jam_selesai' => '15:00', 'harga' => 80000],
            ['kategori' => 'internasional', 'tipe_hari' => 'weekday', 'jam_mulai' => '15:00', 'jam_selesai' => '21:00', 'harga' => 120000],
            ['kategori' => 'standar',       'tipe_hari' => 'weekend', 'jam_mulai' => '08:00', 'jam_selesai' => '15:00', 'harga' => 80000],
            ['kategori' => 'standar',       'tipe_hari' => 'weekend', 'jam_mulai' => '15:00', 'jam_selesai' => '21:00', 'harga' => 130000],
            ['kategori' => 'internasional', 'tipe_hari' => 'weekend', 'jam_mulai' => '08:00', 'jam_selesai' => '15:00', 'harga' => 100000],
            ['kategori' => 'internasional', 'tipe_hari' => 'weekend', 'jam_mulai' => '15:00', 'jam_selesai' => '21:00', 'harga' => 150000],
        ];

        foreach ($tarifs as $data) {
            Tarif::create($data);
        }

        // ===== HARI LIBUR 2026 (SKB 3 Menteri - 17 libur nasional + 8 cuti bersama) =====
        $hariLibur = [
            ['tanggal' => '2026-01-01', 'keterangan' => 'Tahun Baru Masehi', 'tipe' => 'nasional'],
            ['tanggal' => '2026-01-16', 'keterangan' => 'Isra Mikraj Nabi Muhammad SAW', 'tipe' => 'nasional'],
            ['tanggal' => '2026-02-16', 'keterangan' => 'Cuti Bersama Imlek', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-02-17', 'keterangan' => 'Tahun Baru Imlek 2577', 'tipe' => 'nasional'],
            ['tanggal' => '2026-03-18', 'keterangan' => 'Cuti Bersama Nyepi', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-03-19', 'keterangan' => 'Hari Suci Nyepi', 'tipe' => 'nasional'],
            ['tanggal' => '2026-03-20', 'keterangan' => 'Cuti Bersama Idulfitri', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-03-21', 'keterangan' => 'Idulfitri 1447 H', 'tipe' => 'nasional'],
            ['tanggal' => '2026-03-22', 'keterangan' => 'Idulfitri 1447 H', 'tipe' => 'nasional'],
            ['tanggal' => '2026-03-23', 'keterangan' => 'Cuti Bersama Idulfitri', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-03-24', 'keterangan' => 'Cuti Bersama Idulfitri', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-04-03', 'keterangan' => 'Wafat Yesus Kristus', 'tipe' => 'nasional'],
            ['tanggal' => '2026-04-05', 'keterangan' => 'Kebangkitan Yesus Kristus (Paskah)', 'tipe' => 'nasional'],
            ['tanggal' => '2026-05-01', 'keterangan' => 'Hari Buruh Internasional', 'tipe' => 'nasional'],
            ['tanggal' => '2026-05-14', 'keterangan' => 'Kenaikan Yesus Kristus', 'tipe' => 'nasional'],
            ['tanggal' => '2026-05-15', 'keterangan' => 'Cuti Bersama Kenaikan Yesus Kristus', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-05-27', 'keterangan' => 'Iduladha 1447 H', 'tipe' => 'nasional'],
            ['tanggal' => '2026-05-28', 'keterangan' => 'Cuti Bersama Iduladha', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-05-31', 'keterangan' => 'Hari Raya Waisak', 'tipe' => 'nasional'],
            ['tanggal' => '2026-06-01', 'keterangan' => 'Hari Lahir Pancasila', 'tipe' => 'nasional'],
            ['tanggal' => '2026-06-16', 'keterangan' => '1 Muharam Tahun Baru Islam 1448 H', 'tipe' => 'nasional'],
            ['tanggal' => '2026-08-17', 'keterangan' => 'Proklamasi Kemerdekaan RI', 'tipe' => 'nasional'],
            ['tanggal' => '2026-08-25', 'keterangan' => 'Maulid Nabi Muhammad SAW', 'tipe' => 'nasional'],
            ['tanggal' => '2026-12-24', 'keterangan' => 'Cuti Bersama Natal', 'tipe' => 'cuti_bersama'],
            ['tanggal' => '2026-12-25', 'keterangan' => 'Kelahiran Yesus Kristus (Natal)', 'tipe' => 'nasional'],
        ];

        foreach ($hariLibur as $data) {
            HariLibur::create($data);
        }
    }
}
