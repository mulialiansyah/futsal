# Sistem Booking Lapangan Futsal

Sistem booking lapangan futsal berbasis Laravel dengan fitur manajemen admin dan user.

## Fitur Utama

### Admin
- Manajemen lapangan (tambah, edit, hapus, lihat)
- Manajemen tarif (atur harga berdasarkan kategori, tipe hari, dan jam)
- Manajemen hari libur (atur tanggal merah yang dianggap sebagai weekend)
- Manajemen booking (lihat semua booking, ubah status booking)
- Manajemen pembayaran (lihat bukti transfer, verifikasi pembayaran)
- Pembayaran Midtrans terverifikasi otomatis melalui webhook
- Generate laporan penjualan berdasarkan rentang tanggal
- Dashboard dengan statistik (total booking, total lapangan, pembayaran pending)

### User (Penyewa)
- Melihat daftar booking sendiri
- Membuat booking lapangan baru (pilih lapangan, tanggal, jam, durasi)
- Melihat detail booking
- Membatalkan booking
- Melihat denah lapangan dan status ketersediaan per tanggal/jam
- Membayar DP atau pelunasan secara online melalui Midtrans Snap

## Spesifikasi Sistem

- **Jam Operasional**: 08:00 - 21:00
- **Kategori Lapangan**: Standar, Internasional
- **Jenis Lapangan**: Sintetis, Vinyl
- **Tipe Venue**: Indoor, Outdoor
- **Total Lapangan**: 9 (5 Standar, 4 Internasional)
- **Durasi Booking**: 1-4 jam
- **Deadline Pembayaran**: 1 jam setelah booking (booking otomatis batal jika tidak dibayar)

## Harga

### Weekday (Senin-Jumat, tidak termasuk hari libur)
| Kategori     | Jam 08:00-15:00 | Jam 15:00-21:00 |
|--------------|-----------------|-----------------|
| Standar      | Rp 60.000/jam   | Rp 100.000/jam  |
| Internasional| Rp 80.000/jam   | Rp 120.000/jam  |

### Weekend (Sabtu-Minggu, hari libur)
| Kategori     | Jam 08:00-15:00 | Jam 15:00-21:00 |
|--------------|-----------------|-----------------|
| Standar      | Rp 80.000/jam   | Rp 130.000/jam  |
| Internasional| Rp 100.000/jam  | Rp 150.000/jam  |

## Instalasi

1. Clone repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy .env.example ke .env dan konfigurasi database:
   - Untuk MySQL di Laragon:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=futsal_db
     DB_USERNAME=root
     DB_PASSWORD=
     ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```
6. Jalankan development server:
   ```bash
   php artisan serve
   ```
7. Akses aplikasi di http://127.0.0.1:8000

## Konfigurasi Midtrans (Sandbox)

1. Buat atau masuk ke akun [Midtrans Sandbox](https://dashboard.sandbox.midtrans.com/).
2. Salin **Server Key** dan **Client Key** dari menu **Settings → Access Keys** ke file `.env`:
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-...
   MIDTRANS_CLIENT_KEY=SB-Mid-client-...
   MIDTRANS_IS_PRODUCTION=false
   ```
3. Untuk menerima status pembayaran otomatis, aplikasi harus dapat diakses dari internet melalui HTTPS. Isi URL webhook publik:
   ```env
   MIDTRANS_NOTIFICATION_URL=https://domain-anda.com/midtrans/notification
   ```
4. Atur URL notifikasi yang sama pada dashboard Midtrans bila tidak menggunakan `MIDTRANS_NOTIFICATION_URL`, lalu segarkan konfigurasi Laravel:
   ```bash
   php artisan config:clear
   ```

Endpoint webhook memverifikasi `signature_key` dari Midtrans sebelum mengubah status pembayaran dan booking. Jangan memasukkan Server Key ke JavaScript atau repositori.

## Akun Demo

### Admin
- Email: admin@example.com
- Password: password

### User
- Email: test@example.com
- Password: password

## Teknologi

- Laravel 13
- PHP 8.4
- MySQL
- Tailwind CSS
- Blade Template Engine

## Lisensi

Proyek ini open-source di bawah [MIT license](https://opensource.org/licenses/MIT).
