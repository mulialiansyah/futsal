# Changelog

## MVP Core: Pending Payment Lock (Timeout)

### Tanggal

(otomatis: sekarang)

### Ringkasan

Slot lapangan saat booking status `pending` akan terkunci **hanya sementara**. Jika penyewa tidak membayar sampai waktu habis, slot akan dianggap tersedia lagi pada pemeriksaan ketersediaan berikutnya.

---

### Perubahan Database

- **Tambah kolom** `pending_expires_at` pada tabel `bookings`.

File:

- `database/migrations/2026_06_23_155903_create_bookings_table.php`
- `database/migrations/2026_06_27_135258_add_pending_expires_at_to_bookings_table.php`
    - migration aman untuk memastikan kolom ada tanpa reset data

---

### Perubahan Backend (Anti Bentrok + Checkout Menunggu Pembayaran)

- Saat booking dibuat:
    - `status_booking = pending`
    - `pending_expires_at = now() + 60 menit`
- Anti bentrok sekarang mempertimbangkan timeout pending:
    - `dp_dibayar` dan `lunas` selalu mengunci
    - `pending` hanya mengunci selama `pending_expires_at > now()`
    - `batal` tidak mengunci

File:

- `app/Http/Controllers/Customer/BookingController.php`

---

### Perubahan Model

- Cast `pending_expires_at` sebagai `datetime`.

File:

- `app/Models/Booking.php`

---

### Status Migrasi

- Migration kolom `pending_expires_at` sudah dijalankan via `php artisan migrate`.

---

### Catatan

- Jika terminal sempat menampilkan `Nothing to migrate`, itu karena migration yang sama sudah pernah dieksekusi pada environment/DB tersebut.
