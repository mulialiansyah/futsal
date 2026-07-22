<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar admin yang ingin dibuat
        $admins = [
            [
                'name' => 'Admin Baru',
                'email' => 'adminbaru@example.com',
                'password' => 'password123', // Ganti dengan password yang aman
            ],
            // Tambahkan admin lain di sini jika perlu
            // [
            //     'name' => 'Admin Lain',
            //     'email' => 'adminlain@example.com',
            //     'password' => 'password123',
            // ],
        ];

        foreach ($admins as $admin) {
            // Cek apakah email sudah terdaftar
            $existingUser = User::where('email', $admin['email'])->first();
            if (!$existingUser) {
                User::create([
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'password' => Hash::make($admin['password']),
                    'role' => 'admin',
                ]);
                $this->command->info("Admin {$admin['email']} berhasil dibuat!");
            } else {
                $this->command->warn("Admin {$admin['email']} sudah ada!");
            }
        }
    }
}
