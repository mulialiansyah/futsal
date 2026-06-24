# TODO - Perubahan Sementara (Admin Dashboard)

## Ringkasan

Repo sudah memiliki dashboard admin (route + controller + view + layout). Task ini difokuskan untuk memastikan “rapih dan pasti jalan”, terutama dari sisi wiring route/middleware dan konsistensi view.

## Checklist

- [ ] Pastikan middleware alias `admin` terdaftar dan mengarah ke `App\Http\Middleware\AdminMiddleware`
    - Cari lokasi registrasi middleware: biasanya `bootstrap/app.php` (Laravel 10+) atau `app/Http/Kernel.php` (Laravel 9-)
    - Validasi bahwa route prefix `/admin` benar-benar memakai middleware `admin` yang sesuai

- [ ] Validasi koneksi admin dashboard end-to-end
    - [ ] Login pakai user role `admin`
    - [ ] Akses `GET /admin/dashboard`
    - [ ] Pastikan tidak terjadi error relasi/view (mis. `$booking->user`, `$booking->lapangan`)

- [ ] Rapihkan coding view (opsional, jika diperlukan)
    - [ ] Rapihkan formatting Blade & guard terhadap data null (jika ada kasus booking tanpa relasi)

- [ ] Dokumentasi hasil
    - [ ] Catat perbaikan yang dilakukan (file apa yang berubah dan kenapa)

## Note Teknis

- Route admin dashboard sudah terdeteksi lewat `php artisan route:list --path=admin`.
- `AdminMiddleware` sudah ada dan logic-nya: hanya user dengan `role === 'admin'` yang boleh lewat.
- `app/Http/Kernel.php` sempat dicek tapi tidak ditemukan (kemungkinan struktur project berbeda).
