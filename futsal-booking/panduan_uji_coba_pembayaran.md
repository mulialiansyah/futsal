# Panduan Uji Coba: Fitur DP & Pelunasan Cash (Baru)

Dokumen ini menjelaskan langkah-langkah untuk menguji coba fitur **Down Payment (DP) 50%**, **Pelunasan Online oleh Customer**, dan **Pelunasan Cash di Tempat oleh Admin** yang baru saja diimplementasikan.

---

## Prasyarat Sebelum Mulai
Pastikan 3 terminal Anda berjalan normal di local:
1. `php artisan serve` (untuk server website)
2. `npm run dev` (untuk frontend asset)
3. `php artisan schedule:work` (untuk menjalankan pembatalan otomatis di background)

---

## 🧪 Skenario 1: Pembayaran DP 50% & Pelunasan Online

Skenario ini menguji customer membayar DP terlebih dahulu, lalu melunasinya kembali secara online sebelum jatuh tempo (Hari H).

### Langkah 1: Buat Booking Baru (Customer)
1. Login sebagai **Customer**.
2. Masuk ke halaman **Booking Lapangan** (`/customer/booking/create`).
3. Pilih lapangan, tanggal main (**minimal H-2**, misal booking hari Jumat untuk hari Minggu), jam main, dan durasi.
4. Perhatikan bagian **Estimasi Total Harga** — sekarang akan otomatis memunculkan info **Bisa DP Dulu (Min 50%)**.
5. Klik **Booking Sekarang**.

### Langkah 2: Upload Bukti DP 50% (Customer)
1. Setelah berhasil booking, status booking Anda adalah `Pending`.
2. Klik tombol **💳 Bayar DP / Lunas**.
3. Di halaman upload bukti bayar, Anda akan melihat tombol bantuan cepat:
   - **Set DP 50% (Rp XXX)**
   - **Set Lunas 100% (Rp YYY)**
4. Klik tombol **Set DP 50%** (nominal akan terisi otomatis setengah dari harga).
5. Unggah gambar bukti transfer sembarang (.jpg/.png), lalu klik **Kirim Bukti Pembayaran**.
6. Status verifikasi pembayaran akan menjadi `⏳ Menunggu Verifikasi`.

### Langkah 3: Verifikasi DP oleh Admin (Admin)
1. Buka browser lain atau logout lalu login sebagai **Admin**.
2. Masuk to menu **Kelola Pembayaran** (`/admin/pembayaran`).
3. Temukan transaksi baru yang berstatus `Pending`, lalu klik **Detail/Show**.
4. Klik tombol **Terima**.
5. Masuk ke detail booking tersebut. Anda akan melihat status booking berubah menjadi **DP Dibayar** (`dp_dibayar`) dan ada info **Batas Waktu Pelunasan** (setara dengan tanggal & jam main lapangan).

### Langkah 4: Upload Bukti Pelunasan (Customer)
1. Kembali login sebagai **Customer**.
2. Buka menu **Riwayat Booking** atau detail booking yang barusan dibayar DP.
3. Anda akan melihat tombol baru: **💳 Pelunasan**.
4. Sisa tagihan otomatis akan terisi penuh (50% sisanya).
5. Unggah gambar bukti transfer baru, lalu kirim.

### Langkah 5: Verifikasi Pelunasan oleh Admin (Admin)
1. Buka kembali halaman admin pada menu **Kelola Pembayaran**.
2. Terima pembayaran kedua tersebut.
3. Status booking sekarang otomatis berubah dari `dp_dibayar` menjadi **Lunas** (`lunas`).

---

## 🧪 Skenario 2: Pelunasan Langsung Cash di Tempat (Hari H)

Skenario ini menguji ketika customer sudah DP, lalu pas hari H datang ke lapangan membawa uang cash langsung ke admin tanpa upload bukti bayar lagi.

### Langkah 1: Buat Booking & Bayar DP
1. Lakukan proses booking dan bayar DP 50% (ikuti **Langkah 1 s/d 3** dari Skenario 1 di atas).
2. Pastikan status booking sudah berubah menjadi **DP Dibayar** (`dp_dibayar`).

### Langkah 2: Konfirmasi Pelunasan Cash (Admin)
1. Login sebagai **Admin**.
2. Masuk ke menu **Kelola Booking** (`/admin/booking`) dan klik **Detail** pada booking customer yang berstatus `dp_dibayar` tersebut.
3. Di halaman detail booking admin, Anda akan melihat kotak oranye bertuliskan:
   - **💵 Konfirmasi Pelunasan Cash**
   - Di dalamnya ada tombol: `Konfirmasi Pelunasan Cash — Rp XXX` (jumlah sisa tagihan).
4. Klik tombol tersebut dan konfirmasi.
5. Sistem akan otomatis mencatat transaksi pelunasan cash (ditandai label **CASH** tanpa foto bukti transfer) dan mengubah status booking saat itu juga menjadi **Lunas**.

---

## 🧪 Skenario 3: Pembatalan Otomatis (Scheduler)

Skenario ini menguji apakah sistem akan membatalkan otomatis pesanan jika customer yang sudah membayar DP tidak melakukan pelunasan sampai jadwal main (Hari H) tiba.

### Langkah 1: Buat Booking & Bayar DP
1. Lakukan booking dan bayar DP sampai statusnya **DP Dibayar** (`dp_dibayar`).

### Langkah 2: Manipulasi Deadline di Database (Untuk Simulasi)
*Karena tanggal main diuji H-2, kita tidak mungkin menunggu 2 hari di depan komputer agar scheduler berjalan. Kita perlu memanipulasi waktu deadline pelunasan ke masa lalu.*
1. Buka database Anda (misal via phpMyAdmin, DBGate, DBeaver, atau MySQL CLI).
2. Buka tabel `bookings` dan cari baris booking yang baru Anda buat.
3. Ubah kolom `pelunasan_deadline` menjadi waktu yang sudah lewat (misalnya ubah tanggalnya menjadi **kemarin**).

### Langkah 3: Perhatikan Hasilnya
1. Biarkan terminal `php artisan schedule:work` berjalan.
2. Dalam waktu maksimal 1 menit, scheduler akan mengeksekusi perintah command `bookings:release-expired`.
3. Anda akan melihat log di terminal scheduler berbunyi:
   `[2026-07-13 12:00:00] Execution bookings:release-expired ...`
   `✅ 1 booking(s) dibatalkan (uang hangus) karena telat pelunasan.`
4. Cek kembali status booking tersebut di web, statusnya kini sudah berubah menjadi **Batal** (`batal`) dan slot jadwal lapangan tersebut sudah terbuka kembali untuk disewa orang lain.
