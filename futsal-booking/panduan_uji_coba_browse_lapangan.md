# Panduan Uji Coba: Fitur Browse & Detail Lapangan

Panduan ini memandu Anda menguji fitur baru yang memungkinkan customer melihat, mencari, dan
memeriksa ketersediaan slot lapangan sebelum melakukan booking.

---

## Prasyarat

Pastikan 3 terminal ini sedang berjalan:
```
php artisan serve
npm run dev
php artisan schedule:work
```

Akses di browser: **http://127.0.0.1:8000**

---

## Skenario 1: Browse Lapangan

**Tujuan:** Memastikan customer bisa melihat semua lapangan, mencari, dan filter berdasarkan kategori.

### Langkah

1. Login sebagai **Customer** (bukan admin).
2. Perhatikan **navbar** — sekarang terdapat menu **"Cari Lapangan"** dan **"Riwayat Booking"** (menggantikan menu "Dashboard" yang lama).
3. Klik menu **"Cari Lapangan"**.
4. Buka URL: `http://127.0.0.1:8000/customer/lapangan`

### Yang Harus Terlihat

- ✅ Grid semua lapangan yang sudah dibuat admin.
- ✅ Setiap card lapangan menampilkan: **foto, badge kategori, nama, jenis permukaan, tipe area, dan harga mulai dari...**.
- ✅ Label harga hari ini menunjukkan **"Weekday"** atau **"Weekend / Tanggal Merah"** sesuai hari.

### Test Pencarian & Filter

1. Ketik nama lapangan di kolom pencarian → klik **"Cari"** → pastikan hanya lapangan yang sesuai yang tampil.
2. Klik tombol **"Standar"** → hanya lapangan kategori standar yang tampil.
3. Klik tombol **"Internasional"** → hanya lapangan kategori internasional yang tampil.
4. Klik **"Semua"** → semua lapangan tampil kembali.

---

## Skenario 2: Detail Lapangan

**Tujuan:** Memastikan customer bisa melihat foto, spesifikasi, dan daftar tarif lengkap sebuah lapangan.

### Langkah

1. Dari halaman Browse, klik salah satu lapangan.
2. URL akan berubah menjadi: `http://127.0.0.1:8000/customer/lapangan/{id}`

### Yang Harus Terlihat

- ✅ **Foto utama** lapangan ditampilkan di bagian atas.
- ✅ Jika ada lebih dari 1 foto → muncul **thumbnail** di bawah foto utama. Klik thumbnail untuk mengganti foto utama.
- ✅ **Badge kategori** (Standar / Internasional), jenis permukaan, dan tipe area.
- ✅ Grid spesifikasi: Jam Operasional, Kategori, Jenis Permukaan, Tipe Area.
- ✅ **Tabel Daftar Harga** yang menampilkan tarif Weekday dan Weekend per jam.
- ✅ Dua tombol aksi: **"📅 Cek Slot Tersedia"** dan **"⚽ Booking Sekarang"**.

---

## Skenario 3: Kalender Slot Visual

**Tujuan:** Memastikan kalender slot menampilkan status jam yang akurat (Tersedia / Dipesan / Tutup).

### Langkah 1: Buka Kalender

1. Dari halaman Detail Lapangan, klik **"📅 Cek Slot Tersedia"**.
2. URL akan berubah menjadi: `http://127.0.0.1:8000/customer/lapangan/{id}/slots`

### Yang Harus Terlihat

- ✅ Input **pilih tanggal** (minimal H+2 dari hari ini karena aturan booking minimal H-2).
- ✅ Label tipe hari (Weekday / Weekend) sesuai tanggal yang dipilih.
- ✅ Grid jam dari **08:00 hingga 20:00** (13 slot jam).
- ✅ Setiap slot memiliki status:
  - 🟢 **"✅ Tersedia"** → tampil tombol **"Pesan Jam Ini →"** berwarna hijau.
  - 🔴 **"🔴 Sudah Dipesan"** → tidak ada tombol.
  - ⚫ **"🔒 Tutup"** → muncul jika admin menutup lapangan di tanggal tersebut.

### Langkah 2: Test Slot Dipesan

Agar slot merah muncul:
1. Login sebagai **Admin** di tab lain.
2. Buat booking manual atau verifikasi booking customer yang ada untuk lapangan dan tanggal yang sama.
3. Kembali ke halaman kalender slot customer, pilih tanggal yang sama → jam yang dipesan akan berwarna **merah**.

### Langkah 3: Test Slot Tutup

Agar slot abu-abu muncul:
1. Login sebagai **Admin**.
2. Buka menu **"Ketersediaan"**.
3. Tutup lapangan yang sama pada tanggal yang ingin dites.
4. Kembali ke halaman kalender slot customer, pilih tanggal tersebut → **semua slot** akan berwarna abu-abu dengan label "🔒 Tutup".

---

## Skenario 4: Booking dari Kalender Slot (Seamless Flow)

**Tujuan:** Memastikan klik "Pesan Jam Ini" mengisi form booking secara otomatis.

### Langkah

1. Di halaman kalender slot, pilih tanggal yang ada slot hijau.
2. Klik tombol **"Pesan Jam Ini →"** pada salah satu jam yang tersedia.
3. Anda akan diarahkan ke form booking.

### Yang Harus Terisi Otomatis

- ✅ **Lapangan** sudah ter-pilih di dropdown sesuai lapangan yang dilihat.
- ✅ **Tanggal Main** sudah terisi sesuai tanggal yang dipilih di kalender.
- ✅ **Jam Mulai** sudah terisi sesuai jam yang diklik.
- ✅ **Estimasi harga** sudah muncul jika durasi juga diisi.
- ✅ Jika lapangan ditutup di tanggal tersebut, muncul **peringatan merah** dan tombol submit dinonaktifkan.

### Langkah Lanjutan

4. Pilih **Durasi** (misal: 1 jam) → perkiraan harga akan muncul otomatis.
5. Klik **"Konfirmasi Booking"** → booking berhasil dibuat.
6. Customer akan diarahkan ke halaman detail booking untuk melanjutkan pembayaran.

---

## Skenario 5: Booking Langsung dari Detail Lapangan

**Tujuan:** Memastikan tombol "Booking Sekarang" di halaman detail mengisi lapangan secara otomatis.

### Langkah

1. Dari halaman Detail Lapangan, klik **"⚽ Booking Sekarang"**.
2. Form booking terbuka dengan **lapangan sudah ter-pilih** otomatis di dropdown.
3. Customer hanya perlu mengisi: Tanggal, Jam Mulai, dan Durasi.

---

## Ringkasan Alur Lengkap

```
Navbar "Cari Lapangan"
   ↓
Browse Lapangan (search + filter)
   ↓ klik lapangan
Detail Lapangan (foto + spesifikasi + tabel harga)
   ↓
   ├── 📅 Cek Slot Tersedia
   │       ↓
   │   Kalender Jam Visual (08:00 - 20:00)
   │       ↓ klik jam hijau
   │   Form Booking (auto-fill: lapangan + tanggal + jam)
   │       ↓
   │   Booking Berhasil → Upload Pembayaran
   │
   └── ⚽ Booking Sekarang
           ↓
       Form Booking (auto-fill: lapangan saja)
           ↓
       Booking Berhasil → Upload Pembayaran
```
