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
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        User::firstOrCreate(
            ['email' => 'adminbaru@example.com'],
            [
                'name' => 'Admin Baru',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'penyewa',
            ]
        );
        User::firstOrCreate(
            ['email' => 'aloy@gmail.com'],
            [
                'name' => 'aloy',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'penyewa',
            ]
        );

        // ===== DUMMY PENYEWA USERS (30 users) =====
        $dummyUsers = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com'],
            ['name' => 'Siti Rahayu', 'email' => 'siti.rahayu@gmail.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@gmail.com'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@gmail.com'],
            ['name' => 'Rizky Pratama', 'email' => 'rizky.pratama@gmail.com'],
            ['name' => 'Putri Ayu', 'email' => 'putri.ayu@gmail.com'],
            ['name' => 'Eko Kurniawan', 'email' => 'eko.kurniawan@gmail.com'],
            ['name' => 'Rina Wati', 'email' => 'rina.wati@gmail.com'],
            ['name' => 'Fajar Nugraha', 'email' => 'fajar.nugraha@gmail.com'],
            ['name' => 'Maya Sari', 'email' => 'maya.sari@gmail.com'],
            ['name' => 'Dimas Anggara', 'email' => 'dimas.anggara@gmail.com'],
            ['name' => 'Linda Permata', 'email' => 'linda.permata@gmail.com'],
            ['name' => 'Rian Hidayat', 'email' => 'rian.hidayat@gmail.com'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri.handayani@gmail.com'],
            ['name' => 'Adi Prasetyo', 'email' => 'adi.prasetyo@gmail.com'],
            ['name' => 'Wulan Sari', 'email' => 'wulan.sari@gmail.com'],
            ['name' => 'Bayu Setiawan', 'email' => 'bayu.setiawan@gmail.com'],
            ['name' => 'Nurul Hidayah', 'email' => 'nurul.hidayah@gmail.com'],
            ['name' => 'Gilang Ramadhan', 'email' => 'gilang.ramadhan@gmail.com'],
            ['name' => 'Ani Wijaya', 'email' => 'ani.wijaya@gmail.com'],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra.gunawan@gmail.com'],
            ['name' => 'Siska Amalia', 'email' => 'siska.amalia@gmail.com'],
            ['name' => 'Yudi Saputra', 'email' => 'yudi.saputra@gmail.com'],
            ['name' => 'Ratna Dewi', 'email' => 'ratna.dewi@gmail.com'],
            ['name' => 'Doni Pratama', 'email' => 'doni.pratama@gmail.com'],
            ['name' => 'Indah Pertiwi', 'email' => 'indah.pertiwi@gmail.com'],
            ['name' => 'Feri Irawan', 'email' => 'feri.irawan@gmail.com'],
            ['name' => 'Susi Susanti', 'email' => 'susi.susanti@gmail.com'],
            ['name' => 'Agus Salim', 'email' => 'agus.salim@gmail.com'],
        ];

        foreach ($dummyUsers as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role' => 'penyewa',
                ]
            );
        }

        // ===== LAPANGAN (9 total: 5 standar, 4 internasional) =====
        $lapangans = [
            // Standar: 2 sintetis + 3 vinyl | 2 outdoor + 3 indoor
            ['nama_lapangan' => 'Lapangan Standar A', 'kategori' => 'standar', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'outdoor'],
            ['nama_lapangan' => 'Lapangan Standar B', 'kategori' => 'standar', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'outdoor'],
            ['nama_lapangan' => 'Lapangan Standar C', 'kategori' => 'standar', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Lapangan Standar D', 'kategori' => 'standar', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Lapangan Standar E', 'kategori' => 'standar', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],

            // Internasional: 2 sintetis + 2 vinyl | 1 outdoor + 3 indoor
            ['nama_lapangan' => 'Lapangan Inter A', 'kategori' => 'internasional', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'outdoor'],
            ['nama_lapangan' => 'Lapangan Inter B', 'kategori' => 'internasional', 'jenis_lapangan' => 'sintetis', 'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Lapangan Inter C', 'kategori' => 'internasional', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
            ['nama_lapangan' => 'Lapangan Inter D', 'kategori' => 'internasional', 'jenis_lapangan' => 'vinyl',    'tipe_venue' => 'indoor'],
        ];

        foreach ($lapangans as $data) {
            Lapangan::firstOrCreate(
                ['nama_lapangan' => $data['nama_lapangan']],
                $data
            );
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
            Tarif::firstOrCreate(
                [
                    'kategori' => $data['kategori'],
                    'tipe_hari' => $data['tipe_hari'],
                    'jam_mulai' => $data['jam_mulai'],
                    'jam_selesai' => $data['jam_selesai'],
                ],
                $data
            );
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
            HariLibur::firstOrCreate(
                ['tanggal' => $data['tanggal']],
                $data
            );
        }
    }
}
