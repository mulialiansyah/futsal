# Ringkasan Teknologi Proyek Futsal Booking

## Kesimpulan cepat

Proyek ini adalah aplikasi **full-stack monolith Laravel**. Backend dan halaman web berada dalam satu repository dan satu aplikasi Laravel. Aplikasi **tidak memakai React sebagai frontend aktif**; antarmuka yang berjalan dibuat dengan Blade, Tailwind CSS, dan Alpine.js.

## Backend

- **Bahasa:** PHP 8.3+ (mesin PHP lokal saat audit: 8.4.12).
- **Framework:** Laravel 13 (`laravel/framework`).
- **Arsitektur:** MVC Laravel: route, controller, model Eloquent, service, middleware, request validation, migration, seeder, command, dan test PHPUnit.
- **Autentikasi:** Laravel Breeze (`laravel/breeze`) dengan login, registrasi, reset password, konfirmasi password, dan verifikasi email. Ada pemisahan peran `admin` dan `penyewa` melalui middleware `isAdmin`.
- **Pembayaran:** Midtrans Snap melalui paket `midtrans/midtrans-php`. Webhook `POST /midtrans/notification` memverifikasi signature sebelum mengubah status transaksi.
- **Proses terjadwal:** command untuk melepas booking yang kedaluwarsa dan mengirim pengingat bermain.

## Frontend

- **Template/server-side rendering:** Laravel Blade (`resources/views/**/*.blade.php`).
- **Styling:** Tailwind CSS 3, PostCSS, Autoprefixer, dan plugin `@tailwindcss/forms`.
- **Interaksi JavaScript:** Alpine.js 3. Digunakan untuk dropdown, modal, menu mobile, tab/filter, dan state kecil pada halaman.
- **Bundler:** Vite + `laravel-vite-plugin`.
- **HTTP client yang tersedia:** Axios.

### Apakah memakai React?

**Tidak untuk aplikasi yang sedang berjalan.** Ada dua file contoh/prototipe React/TSX di `resources/js/demo.tsx` dan `resources/js/components/ui/ride-booking-form.tsx`, tetapi keduanya tidak dimasukkan oleh `resources/js/app.js` maupun konfigurasi input Vite. Selain itu, paket React dan dependensi prototipe tersebut belum ada di `package.json`. Jadi file itu bukan bagian dari frontend produksi dan sebaiknya dihapus atau disiapkan secara lengkap bila ingin benar-benar migrasi ke React.

## Database dan penyimpanan

- **Database aktif:** MySQL (`DB_CONNECTION=mysql`), database lokal bernama `futsal_db`.
- **ORM:** Laravel Eloquent.
- **Skema:** migration Laravel pada `database/migrations`.
- **Tabel/domain utama:** users, lapangans, lapangan_fotos, tarifs, bookings, pembayarans, hari_liburs, penutupan_lapangans, notifikasis, cache, dan jobs.
- **Cache dan antrean lokal:** driver database. Untuk lingkungan produksi, jalankan queue worker agar job yang diantrikan diproses.
- **Upload:** filesystem Laravel, dengan bukti pembayaran dan foto lapangan dikelola oleh aplikasi.

## Fitur bisnis utama

- Pencarian dan tampilan detail lapangan beserta slot ketersediaan.
- Booking lapangan, validasi bentrok waktu, tarif weekday/weekend, dan batas pembayaran.
- Pembayaran DP atau pelunasan melalui Midtrans serta upload bukti pendukung.
- Dashboard admin untuk lapangan, tarif, hari libur, ketersediaan, booking, pembayaran, dan laporan.
- Notifikasi pengguna/admin dan pengingat jadwal bermain.

## Hasil audit singkat (25 Juli 2026)

- Lint sintaks PHP untuk `app`, `config`, `routes`, dan `tests` tidak menemukan error sintaks.
- Build produksi `npm run build` berhasil.
- Test ketersediaan booking berhasil: 2 test, 10 assertion.
- Formatter Laravel Pint telah diterapkan pada kode PHP aplikasi dan database untuk merapikan import, indentasi, spasi, serta baris kosong.
- Log sebelumnya menunjukkan seed data hari libur yang dijalankan lebih dari sekali dapat memicu data duplikat. Seeder saat ini telah memakai `firstOrCreate` untuk hari libur, sehingga tanggal yang sudah ada tidak dimasukkan ulang.

## Catatan perbaikan berikutnya

- Vite mengeluarkan peringatan untuk gambar publik `/images/navbar-football-monochrome.png`; asetnya ada dan build tetap sukses. Ini peringatan resolusi saat build, bukan kegagalan runtime.
- Jangan menyimpan `MIDTRANS_SERVER_KEY`, kredensial database, atau nilai `.env` lain di Git.
- Jalankan seluruh test setelah perubahan besar: `php artisan test`.
