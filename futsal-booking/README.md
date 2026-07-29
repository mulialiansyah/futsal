# FutsalKite — Platform Booking Lapangan Futsal

Sistem booking lapangan futsal modern berbasis Laravel 11.x dengan tema gelap (dark theme), manajemen admin lengkap, dan integrasi Midtrans Payment Gateway.

## 🎯 Tentang Proyek

FutsalKite adalah platform booking lapangan futsal untuk **1 lokasi** dengan 9 lapangan (5 kategori Standar, 4 kategori Internasional). Sistem ini mendukung 2 role user:
- **Admin**: Mengelola semua data (lapangan, tarif, hari libur, booking, pembayaran, laporan)
- **Penyewa**: Booking lapangan, melihat riwayat, melakukan pembayaran via transfer manual atau Midtrans Snap

## ⚽ Fitur Utama

### 🔧 Dashboard & Manajemen Admin
- Dashboard dengan statistik real-time (total booking, total pendapatan, lapangan aktif, pembayaran pending)
- **Manajemen Lapangan**: Tambah, edit, hapus lapangan dengan detail:
  - Kategori (Standar / Internasional)
  - Jenis lapangan (Sintetis / Vinyl)
  - Tipe venue (Indoor / Outdoor)
  - Upload foto (hanya format JPG, JPEG, PNG — tanpa PDF, tanpa batas ukuran maksimum)
- **Manajemen Tarif**: Atur harga otomatis berdasarkan:
  - Kategori lapangan
  - Tipe hari (Weekday / Weekend / Tanggal Merah)
  - Window jam (08:00-15:00 / 15:00-21:00)
- **Manajemen Hari Libur**: Data hari libur nasional & cuti bersama (2026)
- **Manajemen Booking**:
  - Lihat semua booking dengan detail
  - Ubah status booking: Pending → DP Dibayar → Lunas / Expired / Batal
  - **Konfirmasi Cash**: Admin bisa input pembayaran cash langsung di lokasi
  - Jika booking **Dibatalkan** → Semua pembayaran pending otomatis menjadi "Ditolak"
  - Jika booking **Lunas** (via verifikasi manual atau cash) → Semua pembayaran pending lain (termasuk Midtrans) otomatis ditolak
- **Manajemen Pembayaran**:
  - Lihat semua transaksi pembayaran
  - Verifikasi / tolak bukti transfer manual
  - Integrasi Midtrans Snap (pembayaran online otomatis via webhook)
- **Laporan Penjualan**: Filter berdasarkan rentang tanggal, lapangan, status; menampilkan total booking, pendapatan, rata-rata pendapatan, dan ringkasan per lapangan
- **Ketersediaan Lapangan**: Atur penutupan lapangan sementara

### 👤 Portal Penyewa (Customer)
- Landing page modern dengan slider 9 lapangan, daftar harga transparan
- **Booking Lapangan**:
  - Pilih lapangan, tanggal, jam main, durasi (1-4 jam)
  - Harga dihitung otomatis berdasarkan aturan tarif
- **Riwayat Booking**:
  - Lihat status booking (Menunggu Pembayaran, DP Dibayar, Lunas, Kedaluwarsa, Dibatalkan)
  - Tombol "Menunggu Verifikasi" **hilang otomatis** jika booking sudah dibatalkan
- **Detail Booking**:
  - Hitung mundur deadline pembayaran
  - Riwayat pembayaran lengkap (Midtrans / Transfer manual / Cash)
  - Sisa tagihan dan total dibayar
- **Pembayaran**:
  - Transfer manual (upload bukti transfer, menunggu verifikasi admin)
  - Midtrans Snap (pembayaran online via GoPay, transfer bank, dll)
- **Denah Lapangan**: Visualisasi status ketersediaan per jam
- Notifikasi real-time untuk status booking dan pembayaran

## 🏟️ Data Lapangan

Total **9 lapangan**:

| Kategori       | Nama Lapangan       | Jenis    | Tipe    |
|----------------|---------------------|----------|---------|
| **Standar** (5)| Lapangan Standar A  | Sintetis | Outdoor |
|                | Lapangan Standar B  | Sintetis | Outdoor |
|                | Lapangan Standar C  | Vinyl    | Indoor  |
|                | Lapangan Standar D  | Vinyl    | Indoor  |
|                | Lapangan Standar E  | Vinyl    | Indoor  |
| **Inter** (4)  | Lapangan Inter A    | Sintetis | Outdoor |
|                | Lapangan Inter B    | Sintetis | Indoor  |
|                | Lapangan Inter C    | Vinyl    | Indoor  |
|                | Lapangan Inter D    | Vinyl    | Indoor  |

## 💳 Daftar Harga

### Weekday (Senin-Jumat, bukan hari libur)
| Kategori     | 08:00 – 15:00 | 15:00 – 21:00 |
|--------------|---------------|---------------|
| Standar      | Rp 60.000     | Rp 100.000    |
| Internasional| Rp 80.000     | Rp 120.000    |

### Weekend & Tanggal Merah
| Kategori     | 08:00 – 15:00 | 15:00 – 21:00 |
|--------------|---------------|---------------|
| Standar      | Rp 80.000     | Rp 130.000    |
| Internasional| Rp 100.000    | Rp 150.000    |

**Catatan**: Pembayaran minimal DP 50% (sisa bisa dilunasi di lokasi via cash).

## 🚀 Instalasi & Konfigurasi

### Prasyarat
- PHP 8.4+
- Composer
- Node.js & npm
- MySQL (pakai Laragon direkomendasikan)

### Langkah-langkah Instalasi

1. **Clone repository**
2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment**
   - Copy file `.env.example` menjadi `.env`
   - Atur koneksi database (contoh untuk Laragon MySQL):
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=futsal_db
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - **PENTING (untuk ngrok/tunnel HTTPS)**:
     - Set trusted proxies di `bootstrap/app.php`:
       ```php
       $middleware->trustProxies(at: '*');
       ```
     - Tambahkan di `.env`:
       ```env
       SESSION_SECURE_COOKIE=true
       SESSION_SAME_SITE=lax
       SESSION_DRIVER=file
       ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Seeder** (akan membuat tabel + data default):
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build Asset (Vite)**:
   ```bash
   npm run build
   ```

7. **Jalankan Development Server**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi di `http://127.0.0.1:8000`

## 💳 Konfigurasi Midtrans Payment Gateway

Untuk menggunakan Midtrans Snap (pembayaran online):

1. Daftar / masuk ke [Midtrans Sandbox Dashboard](https://dashboard.sandbox.midtrans.com/)
2. Salin **Server Key** & **Client Key** ke `.env`:
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxx
   MIDTRANS_IS_PRODUCTION=false
   ```
3. **Webhook (wajib untuk verifikasi otomatis)**:
   - Pastikan aplikasi bisa diakses via HTTPS publik (pakai **ngrok** untuk testing):
     ```bash
     ngrok http 8000
     ```
   - Isi URL notifikasi di `.env`:
     ```env
     MIDTRANS_NOTIFICATION_URL=https://domain-ngrok-anda.ngrok-free.dev/midtrans/notification
     ```
   - Atur URL yang sama di Dashboard Midtrans → Settings → Notification URL
   - Clear cache config:
     ```bash
     php artisan config:clear
     ```

## 👥 Akun Demo (setelah migrate:fresh --seed)

### 🔑 Admin
| Email                  | Password   |
|------------------------|------------|
| admin@example.com      | password   |
| adminbaru@example.com  | password123|

### 👤 Penyewa
| Email                  | Password   |
|------------------------|------------|
| test@example.com       | password   |
| aloy@gmail.com         | password   |

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x, PHP 8.4
- **Frontend**: Tailwind CSS, Alpine.js, Vite, Blade Template
- **Database**: MySQL (via Laragon) / SQLite (untuk testing)
- **Payment Gateway**: Midtrans Snap + Webhook Notification
- **Tools**: ngrok (untuk testing webhook HTTPS), Laragon

## 📝 Aturan Bisnis Penting

- **Jam operasional**: 08:00 – 21:00 WIB
- **Minimal booking**: 1 jam, **maksimal**: 4 jam
- **Deadline pembayaran**: 1 jam setelah booking (lebih dari itu → otomatis Expired)
- **Minimal DP**: 50% dari total harga
- **Pelunasan**: Paling lambat H-H jam main (bisa via cash di lokasi yang dikonfirmasi admin)
- **Harga hari libur nasional/cuti bersama**: Mengikuti tarif Weekend
- **Pembatalan oleh admin**: Semua pembayaran pending untuk booking tersebut otomatis ditolak
- **Booking lunas**: Semua pembayaran pending lain (termasuk tagihan Midtrans) otomatis ditolak

## 📄 Lisensi

Proyek ini untuk keperluan tugas UAS Sistem Informasi.
