# Dokumentasi Fitur — Futsal Booking

Dokumen ini merangkum fitur yang tersedia pada aplikasi berdasarkan rute, controller, model, dan halaman yang saat ini ada di proyek.

## Pengunjung dan pelanggan

- **Beranda publik:** menampilkan daftar lapangan dan tarif.
- **Informasi legal:** halaman syarat dan ketentuan serta kebijakan privasi.
- **Autentikasi:** registrasi, masuk pelanggan/admin, lupa dan reset kata sandi, konfirmasi kata sandi, serta verifikasi email.
- **Profil akun:** pelanggan dapat memperbarui profil, kata sandi, atau menghapus akun.
- **Katalog lapangan:** pencarian dan filter kategori lapangan; detail mencakup spesifikasi, foto, dan tarif.
- **Ketersediaan slot:** pelanggan dapat melihat slot per jam, status dipesan, dan penutupan lapangan pada tanggal tertentu.
- **Denah lapangan interaktif:** pelanggan dapat memilih tanggal dan jam untuk melihat seluruh lapangan berdasarkan kategori serta status tersedia, menunggu pembayaran, dipesan, atau ditutup. Lapangan yang tersedia dapat diklik untuk membuka detailnya.
- **Pemesanan:** membuat, melihat, dan membatalkan booking milik sendiri. Sistem menerapkan booking minimal H-2, jam operasional 08.00–21.00, durasi maksimal empat jam, serta validasi bentrok jadwal dan penutupan lapangan.
- **Harga booking:** harga dihitung di server dari kategori lapangan, jam mulai, tipe hari (weekday/weekend), dan hari libur.
- **Pembayaran transfer:** pelanggan mengunggah bukti transfer hingga 5 MB dan nominal pembayaran. DP minimal 50% serta pelunasan didukung.
- **Pembayaran online Midtrans:** pelanggan dapat membayar DP atau pelunasan melalui Snap (misalnya QRIS, e-wallet, transfer bank, atau kartu). Status pembayaran diterima dari webhook Midtrans yang memverifikasi signature, bukan dari browser pelanggan.
- **Batas waktu pembayaran:** booking pending kedaluwarsa setelah satu jam; booking yang telah DP memiliki batas pelunasan sampai jadwal bermain.
- **Notifikasi dalam aplikasi:** notifikasi booking, hasil verifikasi pembayaran, dan informasi penutupan lapangan dapat ditandai sudah dibaca.

## Admin

- **Dashboard:** total booking lunas, jumlah lapangan, pembayaran yang menunggu verifikasi, booking terbaru, dan pendapatan bulan berjalan.
- **Manajemen lapangan:** tambah, lihat, ubah, dan hapus lapangan beserta gambar utama.
- **Manajemen tarif:** pengelolaan tarif berdasarkan kategori, tipe hari, dan rentang jam.
- **Hari libur:** pengelolaan tanggal libur nasional/cuti bersama yang memakai tarif weekend.
- **Ketersediaan lapangan:** menutup lapangan pada rentang tanggal; pelanggan dengan booking terdampak diberi notifikasi reschedule.
- **Manajemen booking:** melihat detail booking dan memperbarui statusnya.
- **Verifikasi pembayaran:** menerima atau menolak bukti transfer, menghitung akumulasi pembayaran, mengubah status booking menjadi DP dibayar atau lunas, serta mencatat pelunasan tunai.
- **Laporan:** filter laporan berdasarkan rentang tanggal, lapangan, dan status; menampilkan total booking, pendapatan, rata-rata pendapatan, dan ringkasan per lapangan.

## Otomasi terjadwal

- Perintah `bookings:release-expired` disiapkan untuk melepas slot booking pending yang melewati batas pembayaran dan membatalkan booking yang melewati batas pelunasan.
- Perintah `futsal:pengingat-main` disiapkan untuk mengirim pengingat H-1 kepada pelanggan yang booking-nya sudah DP atau lunas.

## Catatan teknis

- Rute aktif menggunakan controller pada `app/Http/Controllers/Customer` dan controller berawalan `Admin` pada `app/Http/Controllers/Admin`.
- Sebelum menggunakan Midtrans, isi `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, dan `MIDTRANS_NOTIFICATION_URL` pada `.env`. URL notifikasi harus memakai HTTPS publik dan mengarah ke `/midtrans/notification`.
- Masih terdapat beberapa controller lama/alternatif yang tidak dirujuk oleh `routes/web.php`; controller tersebut tidak digunakan oleh alur aplikasi aktif dan sebaiknya dihapus hanya setelah dipastikan tidak dibutuhkan.
