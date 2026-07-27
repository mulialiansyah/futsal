# Dokumentasi Proyek FutsalKite - Sistem Booking Lapangan Futsal

## 1. Penjelasan Project

### 1.1 Deskripsi Singkat
FutsalKite adalah sistem manajemen booking lapangan futsal berbasis web yang memungkinkan penyewa untuk melakukan reservasi lapangan futsal secara online dan admin untuk mengelola operasional lapangan secara efisien.

### 1.2 Latar Belakang
- **Masalah**: Proses booking lapangan futsal masih manual melalui telepon atau WhatsApp, menyebabkan konflik jadwal dan ketidaksesuaian harga
- **Solusi**: Sistem online yang mengotomatisasi booking, manajemen harga, dan pelaporan pendapatan

### 1.3 Tujuan Project
- Memudahkan penyewa melakukan booking lapangan secara online 24/7
- Mengotomatisasi perhitungan harga berdasarkan kategori, hari, dan jam
- Menghindari konflik jadwal dengan sistem pengecekan ketersediaan real-time
- Menyediakan laporan pendapatan untuk analisis bisnis
- Mengelola hari libur dan penutupan lapangan secara terpusat

### 1.4 Teknologi yang Digunakan
- **Backend**: Laravel 13 (PHP Framework)
- **Frontend**: Blade Templates, TailwindCSS, Alpine.js
- **Database**: MySQL
- **Payment Gateway**: Midtrans (integrasi)
- **Authentication**: Laravel Breeze

### 1.5 Fitur Utama

#### Untuk Penyewa (Customer):
- Registrasi dan login akun
- Melihat daftar lapangan dengan kategori (Standar/Internasional)
- Melihat denah lapangan dengan status ketersediaan real-time
- Booking lapangan dengan perhitungan harga otomatis
- Upload bukti pembayaran (DP)
- Melihat riwayat booking dan status pembayaran
- Notifikasi untuk update booking dan pembayaran

#### Untuk Admin:
- Dashboard dengan ringkasan statistik
- Manajemen lapangan (tambah, edit, hapus)
- Manajemen tarif berdasarkan kategori, hari, dan jam
- Manajemen hari libur nasional dan cuti bersama
- Manajemen penutupan lapangan sementara
- Verifikasi pembayaran DP dan pelunasan
- Laporan pendapatan dengan filter tanggal, lapangan, dan status
- Notifikasi untuk aktivitas penting

---

## 2. Use Case Diagram

### 2.1 Use Case untuk Penyewa

```
┌─────────────────┐
│    Penyewa      │
└────────┬────────┘
         │
         ├──► Registrasi Akun
         ├──► Login
         ├──► Lihat Daftar Lapangan (Publik)
         ├──► Lihat Detail Lapangan (Publik)
         ├──► Cek Ketersediaan Lapangan (Denah)
         ├──► Booking Lapangan
         │    ├──► Pilih Metode Pembayaran (Midtrans/Cash)
         ├──► Lihat Detail Booking
         ├──► Lihat Riwayat Booking
         ├──► Upload Bukti Pembayaran (DP/Pelunasan)
         ├──► Bayar via Midtrans
         ├──► Bayar di Tempat (Cash)
         ├──► Lihat Notifikasi
         ├──► Mark Notifikasi as Read
         ├──► Edit Profile
         └──► Keluar Akun
```

### 2.2 Use Case untuk Admin

```
┌─────────────────┐
│     Admin       │
└────────┬────────┘
         │
         ├──► Login
         ├──► Dashboard
         ├──║ Manajemen Lapangan
         │    ├──► Tambah Lapangan
         │    ├──► Edit Lapangan
         │    ├──► Hapus Lapangan
         │    └──► Upload Foto Lapangan
         ├──║ Manajemen Tarif
         │    ├──► Tambah Tarif
         │    ├──► Edit Tarif
         │    └──► Hapus Tarif
         ├──║ Manajemen Hari Libur
         │    ├──► Tambah Hari Libur
         │    ├──► Edit Hari Libur
         │    └──► Hapus Hari Libur
         ├──║ Manajemen Booking
         │    ├──► Lihat Daftar Booking
         │    ├──► Lihat Detail Booking
         │    └──► Update Status Booking
         ├──║ Verifikasi Pembayaran
         │    ├──► Lihat Daftar Pembayaran
         │    ├──► Lihat Detail Pembayaran
         │    ├──► Verifikasi Transfer (DP/Pelunasan)
         │    └──► Konfirmasi Cash Payment
         ├──║ Laporan Pendapatan
         │    ├──► Lihat Ringkasan
         │    ├──► Filter Laporan
         │    └──► Cetak Laporan
         ├──║ Manajemen Ketersediaan
         │    ├──► Lihat Penutupan Lapangan
         │    ├──► Tambah Penutupan
         │    └──► Hapus Penutupan
         ├──║ Notifikasi
         │    ├──► Lihat Notifikasi
         │    ├──► Mark as Read
         │    └──► Mark All as Read
         └──► Keluar Akun
```

### 2.3 Deskripsi Use Case Utama

#### UC-01: Booking Lapangan
- **Aktor**: Penyewa
- **Deskripsi**: Penyewa dapat melakukan booking lapangan futsal dengan memilih tanggal, jam, dan lapangan yang tersedia
- **Prekondisi**: Penyewa sudah login
- **Flow Utama**:
  1. Penyewa memilih lapangan dari daftar
  2. Penyewa melihat detail dan denah lapangan
  3. Penyewa memilih tanggal dan jam main
  4. Sistem menampilkan harga berdasarkan tarif yang berlaku
  5. Penyewa konfirmasi booking
  6. Sistem membuat booking dengan status "pending"
  7. Sistem menghitung deadline pembayaran (30 menit)
- **Postkondisi**: Booking berhasil dibuat dengan status pending

#### UC-02: Verifikasi Pembayaran
- **Aktor**: Admin
- **Deskripsi**: Admin memverifikasi bukti pembayaran yang diupload oleh penyewa
- **Prekondisi**: Ada pembayaran dengan status "pending"
- **Flow Utama**:
  1. Admin melihat daftar pembayaran pending
  2. Admin membuka detail pembayaran
  3. Admin melihat bukti transfer
  4. Admin memverifikasi (terima/tolak)
  5. Jika diterima, status booking berubah ke "dp_dibayar"
  6. Sistem mengirim notifikasi ke penyewa
- **Postkondisi**: Status pembayaran diperbarui

#### UC-03: Laporan Pendapatan
- **Aktor**: Admin
- **Deskripsi**: Admin dapat melihat laporan pendapatan dengan berbagai filter
- **Prekondisi**: Admin sudah login
- **Flow Utama**:
  1. Admin membuka halaman laporan
  2. Admin melihat ringkasan pendapatan bulan ini
  3. Admin dapat filter berdasarkan tanggal, lapangan, status
  4. Sistem menampilkan ringkasan per lapangan
  5. Sistem menampilkan detail transaksi
  6. Admin dapat mencetak laporan
- **Postkondisi**: Laporan ditampilkan sesuai filter

---

## 3. Gambar Database (Database Schema)

### 3.1 Entity Relationship Diagram (ERD)

```
┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     users       │       │   lapangans     │       │    bookings     │
├─────────────────┤       ├─────────────────┤       ├─────────────────┤
│ id (PK)         │◄──────│ id (PK)         │◄──────│ id (PK)         │
│ name            │       │ nama_lapangan   │       │ user_id (FK)    │
│ email           │       │ alamat          │       │ lapangan_id (FK)│
│ password        │       │ kategori        │       │ tanggal_main    │
│ role            │       │ ukuran          │       │ jam_mulai       │
│ email_verified  │       │ kapasitas       │       │ jam_selesai     │
│ remember_token  │       │ fasilitas       │       │ total_harga     │
│ created_at      │       │ image           │       │ status_booking  │
│ updated_at      │       │ created_at      │       │ payment_deadline│
└─────────────────┘       │ updated_at      │       │ expired_at      │
                          └─────────────────┘       │ created_at      │
                                                     │ updated_at      │
                                                     └─────────────────┘
                                                              │
                                                              ▼
                                                     ┌─────────────────┐
                                                     │  pembayarans    │
                                                     ├─────────────────┤
                                                     │ id (PK)         │
                                                     │ booking_id (FK) │
                                                     │ nominal         │
                                                     │ bukti_transfer  │
                                                     │ status_verifikasi│
                                                     │ created_at      │
                                                     │ updated_at      │
                                                     └─────────────────┘

┌─────────────────┐       ┌─────────────────┐       ┌─────────────────┐
│     tarifs      │       │  hari_liburs    │       │ penutupan_      │
├─────────────────┤       ├─────────────────┤       │ lapangans       │
│ id (PK)         │       │ id (PK)         │       ├─────────────────┤
│ kategori        │       │ tanggal         │       │ id (PK)         │
│ tipe_hari       │       │ keterangan      │       │ lapangan_id (FK)│
│ jam_mulai       │       │ tipe            │       │ tanggal_mulai   │
│ jam_selesai     │       │ created_at      │       │ tanggal_selesai │
│ harga           │       │ updated_at      │       │ keterangan      │
│ created_at      │       └─────────────────┘       │ created_at      │
│ updated_at      │                                   │ updated_at      │
└─────────────────┘                                   └─────────────────┘

┌─────────────────┐
│  notifikasis    │
├─────────────────┤
│ id (PK)         │
│ user_id (FK)    │
│ judul           │
│ pesan           │
│ tipe            │
│ is_read         │
│ created_at      │
│ updated_at      │
└─────────────────┘
```

### 3.2 Deskripsi Tabel

#### users
Menyimpan data pengguna (admin dan penyewa)
- `id`: Primary key
- `name`: Nama lengkap pengguna
- `email`: Email unik untuk login
- `password`: Password terenkripsi
- `role`: Role pengguna (admin/penyewa)
- `email_verified_at`: Timestamp verifikasi email
- `remember_token`: Token untuk remember me
- `created_at`, `updated_at`: Timestamp

#### lapangans
Menyimpan data lapangan futsal
- `id`: Primary key
- `nama_lapangan`: Nama lapangan
- `alamat`: Alamat lengkap
- `kategori`: Kategori (standar/internasional)
- `ukuran`: Ukuran lapangan
- `kapasitas`: Kapasitas pemain
- `fasilitas`: JSON array fasilitas
- `image`: URL foto lapangan
- `created_at`, `updated_at`: Timestamp

#### bookings
Menyimpan data booking/reservasi
- `id`: Primary key
- `user_id`: Foreign key ke users
- `lapangan_id`: Foreign key ke lapangans
- `tanggal_main`: Tanggal main
- `jam_mulai`: Jam mulai booking
- `jam_selesai`: Jam selesai booking
- `total_harga`: Total harga booking
- `metode_pembayaran`: Metode pembayaran (midtrans/cash)
- `status_booking`: Status (pending/dp_dibayar/lunas/expired/batal]
- `payment_deadline`: Deadline pembayaran
- `pelunasan_deadline`: Deadline pelunasan (setelah DP)
- `expired_at`: Waktu expired booking
- `created_at`, `updated_at`: Timestamp

#### pembayarans
Menyimpan data pembayaran
- `id`: Primary key
- `booking_id`: Foreign key ke bookings
- `nominal`: Nominal pembayaran
- `bukti_transfer`: URL bukti transfer
- `status_verifikasi`: Status (pending/diterima/ditolak]
- `created_at`, `updated_at`: Timestamp

#### tarifs
Menyimpan skema harga berdasarkan kategori, hari, dan jam
- `id`: Primary key
- `kategori`: Kategori (standar/internasional]
- `tipe_hari`: Tipe hari (weekday/weekend]
- `jam_mulai`: Jam mulai window harga
- `jam_selesai`: Jam selesai window harga
- `harga`: Harga per jam (decimal]
- `created_at`, `updated_at`: Timestamp

#### hari_liburs
Menyimpan data hari libur
- `id`: Primary key
- `tanggal`: Tanggal hari libur (unique]
- `keterangan`: Keterangan hari libur
- `tipe`: Tipe (nasional/cuti_bersama]
- `created_at`, `updated_at`: Timestamp

#### penutupan_lapangans
Menyimpan data penutupan lapangan sementara
- `id`: Primary key
- `lapangan_id`: Foreign key ke lapangans
- `tanggal_mulai`: Tanggal mulai penutupan
- `tanggal_selesai`: Tanggal selesai penutupan
- `keterangan`: Alasan penutupan
- `created_at`, `updated_at`: Timestamp

#### notifikasis
Menyimpan data notifikasi untuk pengguna
- `id`: Primary key
- `user_id`: Foreign key ke users
- `judul`: Judul notifikasi
- `pesan`: Isi pesan notifikasi
- `tipe`: Tipe notifikasi (booking/pembayaran/pengingat/penutupan]
- `is_read`: Status dibaca (true/false]
- `created_at`, `updated_at`: Timestamp

### 3.3 Relasi Antar Tabel

- `users` ↔ `bookings`: One-to-Many (satu user bisa banyak booking]
- `lapangans` ↔ `bookings`: One-to-Many (satu lapangan bisa banyak booking]
- `bookings` ↔ `pembayarans`: One-to-Many (satu booking bisa banyak pembayaran]
- `lapangans` ↔ `penutupan_lapangans`: One-to-Many (satu lapangan bisa banyak penutupan]
- `users` ↔ `notifikasis`: One-to-Many (satu user bisa banyak notifikasi]

---

## 4. Diagram Proses Bisnis

### 4.1 Proses Booking Lapangan

```
┌─────────────┐
│  Penyewa    │
└──────┬──────┘
       │
       │ 1. Login & Pilih Lapangan
       ▼
┌─────────────────┐
│  Pilih Tanggal  │
│  & Jam Main    │
└──────┬──────────┘
       │
       │ 2. Cek Ketersediaan
       ▼
┌─────────────────┐
│  Lapangan      │─────► Tersedia?
│  Tersedia?     │
└──────┬──────────┘       │
       │                  │
       │ Ya               │ Tidak
       ▼                  │
┌─────────────────┐       │
│  Hitung Harga   │       │
│  (Tarif Otomatis)│      │
└──────┬──────────┘       │
       │                  │
       │ 3. Konfirmasi    │
       ▼                  │
┌─────────────────┐       │
│  Booking        │       │
│  Dibuat         │       │
│  (Pending)      │       │
└──────┬──────────┘       │
       │                  │
       │ 4. Pilih Metode   │
       │    Pembayaran    │
       ▼                  │
┌─────────────────┐       │
│  Metode Bayar?  │
└──────┬──────────┘
       │
       ├──────────────────┬──────────────────┐
       │                  │                  │
       ▼                  ▼                  ▼
┌──────────┐      ┌──────────┐      ┌──────────┐
│ DP Via   │      │ Lunas    │      │ Bayar di │
│ Transfer │      │ Transfer │      │ Tempat   │
│ (Midtrans)│     │ (Midtrans)│     │ (Cash)   │
└────┬─────┘      └────┬─────┘      └────┬─────┘
     │                  │                  │
     │ 5. Upload Bukti  │ 5. Upload Bukti  │ 5. Datang ke
     │    Transfer     │    Transfer     │    Lokasi
     ▼                  ▼                  ▼
┌─────────────┐   ┌─────────────┐   ┌─────────────┐
│  Admin      │   │  Admin      │   │  Admin      │
│  Verifikasi │   │  Verifikasi │   │  Konfirmasi │
└──────┬──────┘   └──────┬──────┘   │  Cash      │
       │                  │           └──────┬──────┘
       │ 6. Diterima?      │ 6. Diterima?      │
       │                  │                  │
       ├─────┬────────────┤ ├─────┬────────────┤
       │     │            │ │     │            │
       │ Ya  │ Tidak      │ │ Ya  │ Tidak      │
       │     │            │ │     │            │
       ▼     ▼            │ ▼     ▼            │
┌──────────┐ ┌──────────┐ │┌──────────┐ ┌──────────┐
│ DP       │ │ Booking  │ ││ Lunas   │ │ Booking  │
│ Dibayar  │ │ Ditolak  │ ││         │ │ Ditolak  │
└─────┬────┘ └────┬─────┘ │└──────────┘ └────┬─────┘
      │           │       │                 │
      │ 7. Pilih   │       │                 │
      │    Metode  │       │                 │
      │ Pelunasan  │       │                 │
      ▼           │       │                 │
┌─────────────────┐       │                 │
│ Metode Pelunasan?│       │                 │
└──────┬──────────┘       │                 │
       │                  │                 │
       ├──────────────────┤                 │
       │                  │                 │
       ▼                  ▼                 │
┌──────────┐      ┌──────────┐             │
│ Transfer │      │ Cash     │             │
│ Pelunasan│      │ (Tunai)  │             │
└────┬─────┘      └────┬─────┘             │
     │                  │                   │
     │ 8. Upload Bukti  │ 8. Bayar di       │
     │    Transfer     │    Lokasi          │
     ▼                  ▼                   │
┌─────────────┐   ┌─────────────┐           │
│  Admin      │   │  Admin      │           │
│  Verifikasi │   │  Verifikasi │           │
└──────┬──────┘   └──────┬──────┘           │
       │                  │                   │
       │ 9. Diterima?      │ 9. Diterima?      │
       │                  │                   │
       ├─────┬────────────┤ ├─────┬────────────┤
       │     │            │ │     │            │
       │ Ya  │ Tidak      │ │ Ya  │ Tidak      │
       │     │            │ │     │            │
       ▼     ▼            │ ▼     ▼            │
┌──────────┐ ┌──────────┐ │┌──────────┐ ┌──────────┐
│ Lunas   │ │ Booking  │ ││ Lunas   │ │ Booking  │
│         │ │ Ditolak  │ ││ (Tunai) │ │ Ditolak  │
└──────────┘ └──────────┘ │└──────────┘ └──────────┘
                 │       │                 │
                 └───────┴─────────────────┘
```

### 4.2 Proses Verifikasi Pembayaran

```
┌─────────────┐
│   Admin     │
└──────┬──────┘
       │
       │ 1. Buka Dashboard
       ▼
┌─────────────────┐
│  Lihat Daftar  │
│  Pembayaran    │
│  Pending       │
└──────┬──────────┘
       │
       │ 2. Pilih Pembayaran
       ▼
┌─────────────────┐
│  Lihat Detail   │
│  & Bukti       │
└──────┬──────────┘
       │
       │ 3. Cek Tipe Pembayaran
       ▼
┌─────────────────┐
│  Tipe Bayar?    │
└──────┬──────────┘
       │
       ├──────────────────┬──────────────────┐
       │                  │                  │
       ▼                  ▼                  ▼
┌──────────┐      ┌──────────┐      ┌──────────┐
│ DP       │      │ Pelunasan│      │ Cash     │
│ Transfer │      │ Transfer │      │ (Tunai)  │
└────┬─────┘      └────┬─────┘      └────┬─────┘
     │                  │                  │
     │ 4. Verifikasi    │ 4. Verifikasi    │ 4. Konfirmasi
     │    Bukti DP      │    Bukti Pelunasan│    Bayar di
     ▼                  ▼                  │    Lokasi
┌─────────────────┐   ┌─────────────────┐   │
│  Valid?         │   │  Valid?         │   │
└──────┬──────────┘   └──────┬──────────┘   │
       │                  │                  │
       ├─────┬────────────┤ ├─────┬────────────┤
       │     │            │ │     │            │
       │ Ya  │ Tidak      │ │ Ya  │ Tidak      │
       │     │            │ │     │            │
       ▼     ▼            │ ▼     ▼            │
┌──────────┐ ┌──────────┐ │┌──────────┐ ┌──────────┐
│ Status   │ │ Status   │ ││ Status   │ │ Status   │
│ DP       │ │ Booking  │ ││ Lunas   │ │ Booking  │
│ Dibayar  │ │ Ditolak  │ ││         │ │ Ditolak  │
└─────┬────┘ └────┬─────┘ │└──────────┘ └────┬─────┘
      │           │       │                 │
      │ 5. Kirim   │       │ 5. Kirim       │
      │    Notif   │       │    Notif       │
      ▼           │       ▼                 │
┌──────────┐     │   ┌──────────┐           │
│ Kirim    │     │   │ Kirim    │           │
│ Notif ke │     │   │ Notif ke │           │
│ Penyewa  │     │   │ Penyewa  │           │
└──────────┘     │   └──────────┘           │
                 │                           │
                 └───────────┬───────────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Status Booking │
                    │  Diperbarui    │
                    └─────────────────┘
```

### 4.3 Proses Laporan Pendapatan

```
┌─────────────┐
│   Admin     │
└──────┬──────┘
       │
       │ 1. Buka Halaman Laporan
       ▼
┌─────────────────┐
│  Lihat Ringkasan│
│  Pendapatan    │
│  Bulan Ini     │
└──────┬──────────┘
       │
       │ 2. Set Filter
       │    (Tanggal, Lapangan, Status)
       ▼
┌─────────────────┐
│  Generate      │
│  Laporan       │
└──────┬──────────┘
       │
       │ 3. Tampilkan Data
       ▼
┌─────────────────┐
│  Ringkasan per │
│  Lapangan      │
└──────┬──────────┘
       │
       │ 4. Tampilkan Detail
       ▼
┌─────────────────┐
│  Detail        │
│  Transaksi     │
└──────┬──────────┘
       │
       │ 5. Cetak/Export
       ▼
┌─────────────────┐
│  PDF/Print     │
└─────────────────┘
```

---

## 5. Demo Project

### 5.1 Cara Menjalankan Project

#### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Node.js & NPM (untuk asset compilation)

#### Langkah-langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd futsal-booking
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**
Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=futsal_booking
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run Migrations**
```bash
php artisan migrate
php artisan db:seed
```

6. **Compile Assets**
```bash
npm run dev
```

7. **Start Development Server**
```bash
php artisan serve
```

8. **Akses Aplikasi**
- URL: `http://localhost:8000`
- Admin: `http://localhost:8000/admin`

### 5.2 Akun Demo

#### Admin
- Email: `adminbaru@example.com`
- Password: `password123`

#### Penyewa (Customer)
- Registrasi baru melalui halaman register

### 5.3 Fitur Demo

#### 1. Booking Lapangan
1. Login sebagai penyewa
2. Buka halaman "Lapangan"
3. Pilih lapangan yang diinginkan
4. Klik "Lihat Detail"
5. Pilih tanggal dan jam main di denah
6. Sistem akan menampilkan harga otomatis
7. Konfirmasi booking
8. Upload bukti pembayaran DP

#### 2. Manajemen Tarif (Admin)
1. Login sebagai admin
2. Buka menu "Tarif"
3. Klik "Tambah Tarif"
4. Pilih kategori (Standar/Internasional]
5. Pilih tipe hari (Weekday/Weekend]
6. Set jam mulai dan jam selesai
7. Input harga (bisa desimal, contoh: 85000.50]
8. Simpan tarif

#### 3. Verifikasi Pembayaran (Admin)
1. Login sebagai admin
2. Buka menu "Booking"
3. Lihat daftar booking dengan status "Pending"
4. Klik booking untuk melihat detail
5. Lihat bukti transfer
6. Klik "Terima" atau "Tolak"
7. Status booking akan diperbarui otomatis

#### 4. Laporan Pendapatan (Admin)
1. Login sebagai admin
2. Buka menu "Laporan"
3. Lihat ringkasan pendapatan bulan ini
4. Set filter tanggal, lapangan, atau status
5. Klik "Filter" untuk generate laporan
6. Lihat ringkasan per lapangan dan detail transaksi
7. Klik "Cetak / PDF" untuk print laporan

#### 5. Manajemen Hari Libur (Admin)
1. Login sebagai admin
2. Buka menu "Hari Libur"
3. Klik "Tambah Hari Libur"
4. Input tanggal dan keterangan
5. Pilih tipe (Nasional/Cuti Bersama]
6. Simpan
7. Harga otomatis berubah ke tarif weekend pada tanggal tersebut

#### 6. Penutupan Lapangan (Admin)
1. Login sebagai admin
2. Buka menu "Ketersediaan"
3. Klik "Tambah Penutupan"
4. Pilih lapangan
5. Set tanggal mulai dan selesai
6. Input keterangan
7. Simpan
8. Lapangan tidak tersedia untuk booking pada periode tersebut

### 5.4 Screenshot Fitur Utama

*(Catatan: Screenshot dapat diambil saat demo presentasi)*

1. **Halaman Login/Register**
   - Desain modern dengan dark theme
   - Loading animation "Kicking Silhouette"

2. **Halaman Daftar Lapangan (Customer)**
   - Grid layout dengan kartu lapangan
   - Harga preview per jam
   - Filter kategori

3. **Denah Lapangan dengan Status**
   - Visual layout lapangan
   - Status warna (tersedia/pending/dipesan/tutup]
   - Picker tanggal dan jam

4. **Form Booking**
   - Ringkasan booking
   - Harga otomatis
   - Upload bukti pembayaran

5. **Dashboard Admin**
   - Statistik ringkas
   - Quick access menu
   - Notifikasi

6. **Manajemen Tarif**
   - Tab Weekday/Weekend
   - Kategori Standar/Internasional
   - Input harga desimal

7. **Laporan Pendapatan**
   - Card analytics dengan circular progress
   - Mini stats (transaksi, booking, rata-rata]
   - Filter laporan
   - Tabel ringkasan dan detail

8. **Notifikasi**
   - Badge unread
   - List notifikasi
   - Mark as read

### 5.5 Testing Scenarios

#### Scenario 1: Booking Berhasil
1. Penyewa login
2. Pilih lapangan "Lapangan 1" (Standar]
3. Pilih tanggal weekday (misal: Senin]
4. Pilih jam 10:00 - 12:00
5. Harga: Rp 100.000/jam × 2 jam = Rp 200.000
6. Konfirmasi booking
7. Upload bukti transfer DP (50% = Rp 100.000]
8. Admin verifikasi dan terima
9. Status berubah ke "DP Dibayar"
10. Penyewa lunas sisa Rp 100.000
11. Status berubah ke "Lunas"

#### Scenario 2: Booking Weekend
1. Penyewa login
2. Pilih lapangan "Lapangan 2" (Internasional]
3. Pilih tanggal weekend (misal: Sabtu]
4. Pilih jam 14:00 - 16:00
5. Harga: Rp 150.000/jam × 2 jam = Rp 300.000 (tarif weekend lebih mahal]
6. Konfirmasi dan upload bukti
7. Admin verifikasi

#### Scenario 3: Hari Libur
1. Admin tambah hari libur "17 Agustus" (Nasional]
2. Penyewa booking pada tanggal 17 Agustus
3. Harga otomatis menggunakan tarif weekend
4. Booking berhasil dengan harga weekend

#### Scenario 4: Lapangan Ditutup
1. Admin tutup "Lapangan 1" tanggal 20-25 Agustus
2. Penyewa coba booking tanggal 22 Agustus
3. Lapangan muncul dengan status "Tutup"
4. Booking tidak dapat dilakukan

#### Scenario 5: Konflik Jadwal
1. Penyewa A booking Lapangan 1 tanggal 1 Sept jam 10-12
2. Penyewa B coba booking Lapangan 1 tanggal 1 Sept jam 11-13
3. Sistem deteksi konflik (overlap jam 11-12]
4. Booking ditolak, lapangan muncul "Dipesan"

---

## 6. Kesimpulan

FutsalKite adalah solusi komprehensif untuk manajemen booking lapangan futsal yang menggabungkan:

- **User Experience**: Interface modern dan intuitif dengan dark theme
- **Otomatisasi**: Perhitungan harga, pengecekan ketersediaan, dan notifikasi otomatis
- **Fleksibilitas**: Manajemen tarif dinamis, hari libur, dan penutupan lapangan
- **Transparansi**: Laporan pendapatan detail dan tracking status booking real-time
- **Skalabilitas**: Dibangun dengan Laravel yang mudah dikembangkan

Project ini siap untuk presentasi dan dapat di-deploy ke production environment dengan konfigurasi yang sesuai.
