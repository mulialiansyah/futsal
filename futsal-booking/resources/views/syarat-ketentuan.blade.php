<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Syarat &amp; Ketentuan — FutsalKite</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display { font-family: 'Anton', sans-serif; letter-spacing: 0.5px; }
    </style>
</head>
<body class="font-sans antialiased bg-white text-neutral-900">

    <!-- ===== NAVBAR ===== -->
    <header class="sticky top-0 z-50 bg-neutral-950/95 backdrop-blur-md border-b border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_2px_rgba(239,68,68,0.7)]"></span>
                    <span class="font-display text-xl text-white">FutsalKite</span>
                </a>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-neutral-300 hover:text-white transition">Dashboard</a>
                    @else
                        <a href="{{ route('login', ['mode' => 'login']) }}" class="text-sm font-bold text-neutral-300 hover:text-white transition">Masuk</a>
                        <a href="{{ route('login', ['mode' => 'register']) }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-600/30">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ===== CONTENT ===== -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3">Legal</div>
        <h1 class="font-display text-3xl sm:text-4xl text-neutral-900 mb-2">Syarat &amp; Ketentuan</h1>
        <p class="text-sm text-neutral-500 mb-10">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="prose prose-neutral max-w-none space-y-8">

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">1. Pendahuluan</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    Selamat datang di FutsalKite. Dengan mengakses dan menggunakan platform ini untuk melakukan
                    pemesanan lapangan futsal, kamu dianggap telah membaca, memahami, dan menyetujui seluruh
                    syarat dan ketentuan yang berlaku di bawah ini.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">2. Akun Pengguna</h2>
                <ul class="list-disc list-inside text-sm text-neutral-600 leading-relaxed space-y-1">
                    <li>Pengguna wajib mendaftarkan akun dengan data yang benar dan valid.</li>
                    <li>Pengguna bertanggung jawab penuh atas kerahasiaan akun dan kata sandi miliknya.</li>
                    <li>FutsalKite berhak menonaktifkan akun yang terindikasi disalahgunakan atau memberikan data palsu.</li>
                </ul>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">3. Proses Booking dan Pembayaran</h2>
                <ul class="list-disc list-inside text-sm text-neutral-600 leading-relaxed space-y-1">
                    <li>Slot lapangan yang dipilih akan di-hold sementara selama proses checkout berlangsung.</li>
                    <li>Booking dinyatakan sah setelah pembayaran diterima dan dikonfirmasi oleh sistem.</li>
                    <li>Harga sewa lapangan mengikuti kategori, hari, dan jam yang berlaku saat booking dilakukan.</li>
                    <li>Bukti pembayaran wajib disimpan sebagai referensi jika terjadi kendala pada jadwal booking.</li>
                </ul>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">4. Pembatalan dan Perubahan Jadwal</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    Pembatalan atau perubahan jadwal booking dapat dilakukan sesuai dengan kebijakan yang berlaku
                    dan mengikuti tenggat waktu yang ditentukan. Pembatalan mendadak atau di luar tenggat waktu
                    berpotensi tidak mendapatkan pengembalian dana.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">5. Kewajiban Penyewa</h2>
                <ul class="list-disc list-inside text-sm text-neutral-600 leading-relaxed space-y-1">
                    <li>Datang tepat waktu sesuai jam yang telah dibooking.</li>
                    <li>Menjaga fasilitas lapangan dan tidak melakukan tindakan yang merusak properti.</li>
                    <li>Bertanggung jawab atas kerusakan fasilitas yang diakibatkan oleh kelalaian penyewa.</li>
                    <li>Mematuhi peraturan yang berlaku di area lapangan.</li>
                </ul>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">6. Batasan Tanggung Jawab</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    FutsalKite tidak bertanggung jawab atas kehilangan barang pribadi, cedera, atau kejadian di
                    luar kendali yang timbul selama penggunaan lapangan. Pengguna disarankan untuk berhati-hati
                    dan menjaga barang bawaan masing-masing.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">7. Perubahan Syarat dan Ketentuan</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    FutsalKite berhak mengubah, memperbarui, atau menghapus sebagian isi syarat dan ketentuan ini
                    sewaktu-waktu. Perubahan akan berlaku sejak dipublikasikan di halaman ini.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">8. Kontak</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    Jika ada pertanyaan terkait syarat dan ketentuan ini, silakan hubungi kami melalui
                    <a href="https://wa.me/62895610031040" target="_blank" rel="noopener" class="text-red-600 font-semibold hover:underline">WhatsApp</a>.
                </p>
            </section>

        </div>
    </main>

    <footer class="border-t border-neutral-200 bg-neutral-50 py-6 text-center text-xs text-neutral-400">
        &copy; {{ date('Y') }} FutsalKite. Semua hak dilindungi.
    </footer>

</body>
</html>
