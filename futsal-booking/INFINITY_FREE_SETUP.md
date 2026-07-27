# Panduan Deployment ke Infinity Free

## Persiapan Sebelum Upload

### 1. Export Database Local
```bash
# Via phpMyAdmin:
# - Buka phpMyAdmin local
# - Pilih database futsal_db
# - Export → SQL
# - Simpan sebagai futsal_db.sql
```

### 2. Generate App Key
```php
// Jalankan ini di tinker atau buat file PHP sementara
echo 'base64:' . base64_encode(random_bytes(32));
```

### 3. Clear Cache Local
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Upload ke Infinity Free

### 4. Reorganisasi Folder (KRUSIAL!)
Infinity Free menggunakan `htdocs` sebagai webroot, tapi Laravel butuh folder `public/` sebagai webroot untuk keamanan.

**Struktur yang benar:**
```
/htdocs
  ├── index.php (modified)
  ├── .htaccess
  ├── build/ (if exists)
  └── storage/ (symlink to ../laravel_app/storage/app/public)

/laravel_app (folder di LUAR htdocs)
  ├── app/
  ├── bootstrap/
  ├── config/
  ├── database/
  ├── public/ (original - ignore)
  ├── resources/
  ├── routes/
  ├── storage/
  ├── vendor/
  ├── .env
  └── artisan
```

**Langkah-langkah:**
1. Di local, copy file `public/index.php.infinityfree` → rename jadi `index.php` (backup original dulu)
2. Zip folder `vendor` di local (lebih cepat upload)
3. Upload ke Infinity Free:
   - Upload file `public/index.php` (modified) → ke `htdocs/`
   - Upload file `public/.htaccess` → ke `htdocs/`
   - Upload folder `public/build/` (if exists) → ke `htdocs/`
   - Upload zip file `vendor.zip` → ke folder di LUAR `htdocs/` (misal: `/laravel_app/vendor.zip`)
   - Extract `vendor.zip` di server
   - Upload semua folder lain (app, bootstrap, config, database, resources, routes, storage) → ke `/laravel_app/`
4. Buat file `.env` di `/laravel_app/` (bukan di htdocs)

**Modified index.php sudah disiapkan:**
- File `public/index.php.infinityfree` sudah dibuat dengan path yang disesuaikan untuk folder structure baru
- Rename jadi `index.php` sebelum upload ke htdocs

### 5. Import Database di Infinity Free
1. Buka Control Panel Infinity Free
2. Klik "MySQL Databases"
3. Buat database baru
4. Buka phpMyAdmin
5. Import file `futsal_db.sql`

### 6. Buat File .env di Infinity Free
Buat file `.env` di folder `/laravel_app/` (BUKAN di htdocs) dengan isi:

```env
APP_NAME="Futsal Booking"
APP_ENV=production
APP_KEY=base64:PASTE_APP_KEY_DISINI
APP_DEBUG=false
APP_URL=https://your-site.infinityfreeapp.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=sqlXXX.infinityfree.com
DB_PORT=3306
DB_DATABASE=your_db_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_MERCHANT_ID=your_midtrans_merchant_id
MIDTRANS_IS_PRODUCTION=false

CRON_SECRET=generate_random_secret_here
```

**Ganti dengan data dari Infinity Free:**
- `APP_URL`: URL site Anda
- `DB_HOST`: Dari MySQL Databases di Control Panel
- `DB_DATABASE`: Nama database yang dibuat
- `DB_USERNAME`: Username database
- `DB_PASSWORD`: Password database
- `APP_KEY`: App key yang di-generate
- `MIDTRANS_*`: Credential Midtrans Anda
- `CRON_SECRET`: Random string untuk proteksi cron route (misal: `abc123xyz789`)

### 7. Set Permissions
Via File Manager atau FTP:
- Folder `/laravel_app/storage` → 755 atau 777
- Folder `/laravel_app/bootstrap/cache` → 755 atau 777
- Folder `/laravel_app/storage/framework/cache/*` → 755 atau 777
- Folder `/laravel_app/storage/framework/views/*` → 755 atau 777

### 8. Setup Storage Link Manual
Karena tidak bisa jalankan `php artisan storage:link` di Infinity Free:

**Opsi 1: Gunakan Route Temporary (Recommended)**
1. Akses URL: `https://your-site.infinityfreeapp.com/artisan/storage-link?token=YOUR_CRON_SECRET`
2. Setelah berhasil, hapus route `/artisan/storage-link` dari `routes/web.php` (line 117-124)

**Opsi 2: Manual Copy**
1. Buat folder `storage` di `htdocs/`
2. Copy semua isi dari `/laravel_app/storage/app/public/` ke `htdocs/storage/`
3. Pastikan permissions folder `htdocs/storage` → 755 atau 777

### 9. Clear Cache Manual
Hapus isi folder berikut (di `/laravel_app/`):
- `storage/framework/cache/data/*`
- `storage/framework/views/*`
- `bootstrap/cache/config.php`
- `bootstrap/cache/services.php`
- `bootstrap/cache/packages.php`

## Solusi untuk Scheduled Commands

Infinity Free tidak support cron jobs. Gunakan external cron service:

### Opsi 1: cron-job.org (Gratis)
1. Daftar di https://cron-job.org
2. Buat cron job baru:
   - URL: `https://your-site.infinityfreeapp.com/artisan/schedule?token=YOUR_CRON_SECRET`
   - Interval: Every 5 minutes
3. Route sudah disiapkan dengan token protection di `routes/web.php` (line 108-115)

### Opsi 2: EasyCron (Gratis)
1. Daftar di https://www.easycron.com
2. Buat cron job dengan URL yang sama (dengan token)
3. Set interval sesuai kebutuhan

**Security Note:**
- Route `/artisan/schedule` dilindungi dengan token check
- Token diambil dari `CRON_SECRET` di `.env`
- Tanpa token yang benar, route akan return 403 Forbidden

## Keterbatasan Infinity Free

### Tidak Support:
- ❌ Cron jobs (gunakan external service)
- ❌ Queue worker (sudah set ke sync)
- ❌ File upload besar (limit ~2MB)
- ❌ Composer/Artisan langsung

### Workarounds:
- ✅ Scheduled commands → External cron service
- ✅ Queue → Set ke sync driver
- ✅ File upload → Compress gambar atau pakai external storage
- ✅ Composer → Upload folder `vendor` yang sudah ada

## Troubleshooting

### Error 500
- Cek file `.env` sudah dibuat dengan benar
- Pastikan permissions folder `storage` dan `bootstrap/cache` sudah 755/777
- Clear cache manual

### Database Connection Error
- Cek kredensial database di `.env`
- Pastikan database sudah dibuat di Infinity Free
- Cek DB_HOST dari Control Panel Infinity Free

### Scheduled Commands Tidak Jalan
- Pastikan external cron service sudah di-setup
- Cek URL cron job sudah benar
- Cek route `/artisan/schedule` sudah ditambahkan

## Catatan Penting

1. **Scheduled Commands**: Fitur pengingat booking tidak akan berjalan otomatis tanpa external cron service
2. **File Upload**: Limit kecil, pertimbangkan untuk compress gambar
3. **Performance**: Infinity Free adalah hosting gratis, performa mungkin tidak sebaik hosting berbayar
4. **Backup**: Lakukan backup database secara manual berkala

## Alternatif Hosting (Lebih Baik)

Jika membutuhkan fitur lengkap, pertimbangkan:
- **VPS**: DigitalOcean ($5/bulan), Linode ($5/bulan)
- **Shared Hosting**: Niagahoster, IDCloudHost (support cron jobs)
- **PaaS**: Render (free tier), Railway (free tier), Fly.io (free tier)
