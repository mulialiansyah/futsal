<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kebijakan Privasi — FutsalKite</title>

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
        <h1 class="font-display text-3xl sm:text-4xl text-neutral-900 mb-2">Kebijakan Privasi</h1>
        <p class="text-sm text-neutral-500 mb-10">Terakhir diperbarui: {{ date('d F Y') }}</p>

        <div class="prose prose-neutral max-w-none space-y-8">

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">1. Pendahuluan</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    FutsalKite menghargai privasi setiap pengguna. Kebijakan ini menjelaskan bagaimana kami
                    mengumpulkan, menggunakan, menyimpan, dan melindungi data pribadi yang kamu berikan saat
                    menggunakan platform ini.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">2. Informasi yang Kami Kumpulkan</h2>
                <ul class="list-disc list-inside text-sm text-neutral-600 leading-relaxed space-y-1">
                    <li>Data akun: nama, alamat email, nomor telepon, dan kata sandi (terenkripsi).</li>
                    <li>Data transaksi: riwayat booking, jadwal, dan status pembayaran.</li>
                    <li>Data teknis: alamat IP, jenis perangkat, dan aktivitas penggunaan platform.</li>
                </ul>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">3. Penggunaan Data</h2>
                <ul class="list-disc list-inside text-sm text-neutral-600 leading-relaxed space-y-1">
                    <li>Memproses dan mengonfirmasi booking lapangan.</li>
                    <li>Mengirimkan notifikasi terkait status booking dan pembayaran.</li>
                    <li>Meningkatkan kualitas layanan dan pengalaman pengguna di platform.</li>
                    <li>Menghubungi pengguna jika diperlukan terkait transaksi yang sedang berjalan.</li>
                </ul>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">4. Penyimpanan dan Keamanan Data</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    Data pengguna disimpan dengan langkah-langkah keamanan yang wajar untuk mencegah akses,
                    perubahan, atau pengungkapan yang tidak sah. Kata sandi disimpan dalam bentuk terenkripsi
                    dan tidak dapat diakses secara langsung oleh pihak mana pun.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">5. Pembagian Data ke Pihak Ketiga</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    FutsalKite tidak menjual atau menyewakan data pribadi pengguna kepada pihak ketiga.
                    Data hanya dibagikan kepada penyedia layanan pembayaran sepanjang diperlukan untuk
                    memproses transaksi, atau jika diwajibkan oleh peraturan perundang-undangan yang berlaku.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">6. Hak Pengguna</h2>
                <ul class="list-disc list-inside text-sm text-neutral-600 leading-relaxed space-y-1">
                    <li>Mengakses dan memperbarui data pribadi melalui halaman akun.</li>
                    <li>Meminta penghapusan akun beserta data terkait, sesuai ketentuan yang berlaku.</li>
                    <li>Mengajukan pertanyaan atau keberatan terkait penggunaan data pribadinya.</li>
                </ul>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">7. Perubahan Kebijakan</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    Kebijakan privasi ini dapat diperbarui sewaktu-waktu mengikuti perkembangan layanan.
                    Perubahan akan berlaku sejak dipublikasikan di halaman ini.
                </p>
            </section>

            <section>
                <h2 class="font-bold text-lg text-neutral-900 mb-2">8. Kontak</h2>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    Jika ada pertanyaan terkait kebijakan privasi ini, silakan hubungi kami melalui
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
