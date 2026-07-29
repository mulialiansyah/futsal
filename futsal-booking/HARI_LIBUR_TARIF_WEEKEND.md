# Daftar Hari Libur — Otomatis Pakai Tarif Weekend

Sejak update **29 Juli 2026**, semua tanggal di bawah ini otomatis dikenakan **tarif weekend** meskipun jatuh di hari kerja (Senin–Jumat).

> Admin tidak perlu input harga manual. Cukup catat tanggalnya di `/admin/hari-libur`, sistem akan otomatis menyamakan ke tarif weekend saat customer booking.

---

## 2026

| Tanggal | Hari | Keterangan | Tipe |
|---|---|---|---|
| 2026-01-01 | Kamis | Tahun Baru Masehi | 🔴 Nasional |
| 2026-01-16 | Jumat | Isra Mikraj Nabi Muhammad SAW | 🔴 Nasional |
| 2026-02-16 | Senin | Cuti Bersama Imlek | 🟡 Cuti Bersama |
| 2026-02-17 | Selasa | Tahun Baru Imlek 2577 | 🔴 Nasional |
| 2026-03-18 | Rabu | Cuti Bersama Nyepi | 🟡 Cuti Bersama |
| 2026-03-19 | Kamis | Hari Suci Nyepi | 🔴 Nasional |
| 2026-03-20 | Jumat | Cuti Bersama Idulfitri | 🟡 Cuti Bersama |
| 2026-03-21 | Sabtu | Idulfitri 1447 H | 🔴 Nasional |
| 2026-03-22 | Minggu | Idulfitri 1447 H | 🔴 Nasional |
| 2026-03-23 | Senin | Cuti Bersama Idulfitri | 🟡 Cuti Bersama |
| 2026-03-24 | Selasa | Cuti Bersama Idulfitri | 🟡 Cuti Bersama |
| 2026-04-03 | Jumat | Wafat Yesus Kristus | 🔴 Nasional |
| 2026-04-05 | Minggu | Kebangkitan Yesus Kristus (Paskah) | 🔴 Nasional |
| 2026-05-01 | Jumat | Hari Buruh Internasional | 🔴 Nasional |
| 2026-05-14 | Kamis | Kenaikan Yesus Kristus | 🔴 Nasional |
| 2026-05-15 | Jumat | Cuti Bersama Kenaikan Yesus Kristus | 🟡 Cuti Bersama |
| 2026-05-27 | Rabu | Iduladha 1447 H | 🔴 Nasional |
| 2026-05-28 | Kamis | Cuti Bersama Iduladha | 🟡 Cuti Bersama |
| 2026-05-31 | Minggu | Hari Raya Waisak | 🔴 Nasional |
| 2026-06-01 | Senin | Hari Lahir Pancasila | 🔴 Nasional |
| 2026-06-16 | Selasa | 1 Muharam / Tahun Baru Islam 1448 H | 🔴 Nasional |
| 2026-08-17 | Senin | Proklamasi Kemerdekaan RI | 🔴 Nasional |
| 2026-08-25 | Selasa | Maulid Nabi Muhammad SAW | 🔴 Nasional |
| 2026-12-24 | Kamis | Cuti Bersama Natal | 🟡 Cuti Bersama |
| 2026-12-25 | Jumat | Kelahiran Yesus Kristus (Natal) | 🔴 Nasional |

---

## Keterangan Tipe

| Simbol | Tipe | Penjelasan |
|---|---|---|
| 🔴 | Nasional | Libur nasional resmi pemerintah |
| 🟡 | Cuti Bersama | Cuti bersama yang ditetapkan pemerintah |

---

## Tanggal yang Jatuh di Hari Kerja (Paling Terdampak)

Tanggal-tanggal ini jatuh di **Senin–Jumat** sehingga tanpa fitur ini akan dihitung sebagai weekday. Dengan update ini, semua otomatis kena tarif weekend:

| Tanggal | Hari | Keterangan |
|---|---|---|
| 2026-01-01 | **Kamis** | Tahun Baru Masehi |
| 2026-01-16 | **Jumat** | Isra Mikraj |
| 2026-02-16 | **Senin** | Cuti Bersama Imlek |
| 2026-02-17 | **Selasa** | Tahun Baru Imlek |
| 2026-03-18 | **Rabu** | Cuti Bersama Nyepi |
| 2026-03-19 | **Kamis** | Hari Suci Nyepi |
| 2026-03-20 | **Jumat** | Cuti Bersama Idulfitri |
| 2026-03-23 | **Senin** | Cuti Bersama Idulfitri |
| 2026-03-24 | **Selasa** | Cuti Bersama Idulfitri |
| 2026-04-03 | **Jumat** | Wafat Yesus Kristus |
| 2026-05-01 | **Jumat** | Hari Buruh |
| 2026-05-14 | **Kamis** | Kenaikan Yesus Kristus |
| 2026-05-15 | **Jumat** | Cuti Bersama Kenaikan Yesus |
| 2026-05-27 | **Rabu** | Iduladha |
| 2026-05-28 | **Kamis** | Cuti Bersama Iduladha |
| 2026-06-01 | **Senin** | Hari Lahir Pancasila |
| 2026-06-16 | **Selasa** | Tahun Baru Islam |
| 2026-08-17 | **Senin** | HUT RI |
| 2026-08-25 | **Selasa** | Maulid Nabi |
| 2026-12-24 | **Kamis** | Cuti Bersama Natal |
| 2026-12-25 | **Jumat** | Natal |

---

## File yang Diubah (Update 29 Juli 2026)

| File | Perubahan |
|---|---|
| `app/Services/PricingService.php` | `isWeekend()` kini support parameter `?array $holidayDates` untuk efisiensi dalam loop, tetap fallback ke query DB jika tidak di-pass |
| `app/Http/Controllers/Customer/LapanganController.php` | Ganti 2 manual check `isWeekend()` → pakai `PricingService::isWeekend()`. Tambah import `PricingService` |
| `app/Http/Controllers/Customer/BookingController.php` | Ganti manual check di `getDatesWithPriceRange()` → `PricingService::isWeekend($date, $holidayStrings)` (preloaded, tidak query per-iterasi) |
