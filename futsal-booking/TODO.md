# TODO - MVP Futsal Booking (Laravel 13)

## Checklist MVP (dari requirement)

- [x] Role Admin & Penyewa (middleware + route prefix)
- [x] Penyewa cari slot kosong (via form booking)
- [x] Booking slot (status awal pending)
- [x] Cegah bentrok sewa lapangan
- [x] Checkout/pembayaran: pending menunggu pembayaran
- [x] Slot otomatis tersedia lagi jika pending tidak dibayar (timeout)
- [ ] Admin: manajemen harga
- [ ] Admin: validasi pembayaran (verifikasi DP)
- [ ] Admin: ketersediaan lapangan
- [x] Admin: gambar asli lapangan (lapangan foto)
- [ ] Admin: cetak laporan penyewaan (dari tanggal/bulan/tahun)
- [x] Visualisasi lapangan (warna berubah saat booking) - [butuh UI slot grid]
- [ ] Integrasi payment gateway lokal sandbox (plus)
- [ ] Hosting gratis (fitur deploy)

## Perubahan yang sudah dilakukan

- [x] Tambah `pending_expires_at` pada bookings table (migration)
- [x] Saat booking dibuat (pending), set `pending_expires_at = now() + 60 menit`
- [x] Anti bentrok: `pending` hanya mengunci selama belum expired
- [x] Bentrok yang mengunci: `pending (belum expired)`, `dp_dibayar`, `lunas`

## Catatan penting tentang database

- `php artisan migrate` mengembalikan "Nothing to migrate" sehingga kolom `pending_expires_at` mungkin belum benar-benar ada di database Anda.
- Cara aman (recommended): jalankan migration tambahan yang hanya menambah kolom tersebut (tanpa reset data).

## Langkah berikutnya (prioritas)

1. [ ] Buat migration baru: add kolom `pending_expires_at` jika belum ada (tanpa migrate:fresh)
2. [ ] Pastikan Bentrok & Checkout benar-benar membaca kolom `pending_expires_at` di DB
3. [ ] Tambahkan fitur “ketersediaan slot” berbasis UI grid + warna berubah saat booking
4. [ ] Admin: laporan penyewaan (filter tanggal/bulan/tahun)
5. [ ] Integrasi payment gateway sandbox (flow & status)
6. [ ] Setup hosting gratis
