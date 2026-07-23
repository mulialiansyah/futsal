<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FutsalKite — Platform Booking Lapangan Futsal Modern</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display { font-family: 'Anton', sans-serif; letter-spacing: 0.5px; }
        
        /* Slider styles */
        .slider-container {
            position: relative;
            overflow: hidden;
        }
        .slider-track {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slider-slide {
            min-width: 100%;
            flex-shrink: 0;
        }
        @media (min-width: 768px) {
            .slider-slide {
                min-width: 50%;
            }
        }
        @media (min-width: 1024px) {
            .slider-slide {
                min-width: 33.333%;
            }
        }
        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }
        .slider-btn:hover {
            background: #f3f4f6;
        }
        .slider-btn.prev {
            left: 16px;
        }
        .slider-btn.next {
            right: 16px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-white text-neutral-900">

    <!-- ===== NAVBAR ===== -->
    <header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 relative bg-neutral-950/95 backdrop-blur-md border-b border-white/10 overflow-hidden">
        <!-- ambient glow -->
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-24 left-10 w-72 h-72 rounded-full bg-red-600/20 blur-3xl"></div>
            <div class="absolute -top-24 right-24 w-72 h-72 rounded-full bg-amber-400/10 blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_2px_rgba(239,68,68,0.7)]"></span>
                    <span class="font-display text-xl text-white">FutsalKite</span>
                </a>

                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-neutral-300 hover:text-white transition">Dashboard</a>
                    @else
                        <a href="{{ route('login', ['mode' => 'login']) }}" class="text-sm font-bold text-neutral-300 hover:text-white transition">Masuk</a>
                        <a href="{{ route('login', ['mode' => 'register']) }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-600/30">
                            Daftar
                        </a>
                    @endauth
                </div>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-neutral-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div x-show="mobileOpen" x-cloak class="md:hidden pb-4 space-y-1">
                <div class="pt-3 flex gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex-1 text-center px-4 py-2 rounded-full border border-white/20 text-sm font-bold text-white">Dashboard</a>
                    @else
                        <a href="{{ route('login', ['mode' => 'login']) }}" class="flex-1 text-center px-4 py-2 rounded-full border border-white/20 text-sm font-bold text-white">Masuk</a>
                        <a href="{{ route('login', ['mode' => 'register']) }}" class="flex-1 text-center px-4 py-2 rounded-full bg-red-600 text-white text-sm font-bold">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="relative">
        <div class="relative h-[560px] overflow-hidden">
            <img src="{{ asset('images/bg-landing.jpg') }}"
                 onerror="this.src='https://images.unsplash.com/photo-1551958219-acbc608c6377?q=80&w=1920&auto=format&fit=crop'"
                 alt="Lapangan Futsal FutsalKite" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-[11px] font-bold tracking-widest uppercase text-amber-300 mb-5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-300"></span>
                        Mall Cengkareng &middot; Lantai 9
                    </div>
                    <h1 class="font-display text-white text-4xl sm:text-5xl leading-[1.05] mb-5">
                        Main sekarang,<br><span class="text-red-500">gaya kamu sendiri.</span>
                    </h1>
                    <p class="text-neutral-200 text-base leading-relaxed mb-8 max-w-md">
                        9 lapangan dengan kategori dan harga berbeda, jadwal real-time, dan proses booking cepat — tanpa drama rebutan slot.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('login', ['mode' => 'register']) }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition shadow-lg shadow-red-600/30">
                            Ayo Booking
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/></svg>
                        </a>
                        <a href="#lapangan" class="inline-flex items-center px-7 py-3.5 rounded-full bg-white/10 border border-white/30 text-white text-sm font-bold hover:bg-white/20 transition backdrop-blur-sm">
                            Lihat Lapangan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat strip -->
        <div class="bg-neutral-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-3 divide-x divide-white/10 text-center">
                <div>
                    <div class="font-display text-2xl sm:text-3xl text-white">9</div>
                    <div class="text-[11px] sm:text-xs uppercase tracking-wide text-neutral-400 font-semibold">Lapangan</div>
                </div>
                <div>
                    <div class="font-display text-2xl sm:text-3xl text-white">60rb–150rb</div>
                    <div class="text-[11px] sm:text-xs uppercase tracking-wide text-neutral-400 font-semibold">Per Jam</div>
                </div>
                <div>
                    <div class="font-display text-2xl sm:text-3xl text-white">24/7</div>
                    <div class="text-[11px] sm:text-xs uppercase tracking-wide text-neutral-400 font-semibold">Booking Online</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SLIDER LAPANGAN ===== -->
    <section id="lapangan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-12">
            <div class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3">Lapangan Kami</div>
            <h2 class="font-display text-3xl sm:text-4xl text-neutral-900">Pilih Lapangan Favoritmu</h2>
        </div>

        @if($lapangans->count() > 0)
            <div class="slider-container" x-data="{
                currentSlide: 0,
                totalSlides: {{ $lapangans->count() }},
                slidesPerView: window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1),
                maxSlide() {
                    return Math.max(0, this.totalSlides - this.slidesPerView);
                },
                next() {
                    if (this.currentSlide < this.maxSlide()) {
                        this.currentSlide++;
                    }
                },
                prev() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    }
                },
                updateSlidesPerView() {
                    this.slidesPerView = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
                    if (this.currentSlide > this.maxSlide()) {
                        this.currentSlide = this.maxSlide();
                    }
                }
            }"
            x-init="window.addEventListener('resize', () => updateSlidesPerView())">
                
                <button class="slider-btn prev" @click="prev()" x-show="currentSlide > 0">
                    <svg class="w-6 h-6 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <div class="slider-track" :style="'transform: translateX(-' + (currentSlide * (100 / slidesPerView)) + '%)'">
                    @foreach($lapangans as $lapangan)
                        <div class="slider-slide px-3">
                            <div class="rounded-2xl border border-neutral-200 overflow-hidden bg-white shadow-sm hover:shadow-lg transition-shadow">
                                <div class="relative h-48 overflow-hidden">
                                    @php
                                        $imgUrl = $lapangan->foto_utama ? $lapangan->foto_utama->url : 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=800&auto=format&fit=crop';
                                    @endphp
                                    <img src="{{ $imgUrl }}"
                                         alt="{{ $lapangan->nama_lapangan }}"
                                         class="w-full h-full object-cover">
                                    <div class="absolute top-3 left-3">
                                        <span class="px-2.5 py-1 rounded-full bg-black/50 backdrop-blur-sm text-white text-xs font-semibold">
                                            {{ $lapangan->kategori_label }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <h3 class="font-bold text-lg text-neutral-900 mb-1">{{ $lapangan->nama_lapangan }}</h3>
                                    <p class="text-sm text-neutral-500">{{ $lapangan->deskripsi_singkat }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <button class="slider-btn next" @click="next()" x-show="currentSlide < maxSlide()">
                    <svg class="w-6 h-6 text-neutral-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        @else
            <div class="text-center py-16 border-2 border-dashed border-neutral-300 rounded-2xl">
                <p class="text-neutral-500">Belum ada lapangan yang ditambahkan.</p>
            </div>
        @endif
    </section>

    <!-- ===== TARIF ===== -->
    <section id="tarif" class="bg-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="text-xs font-bold uppercase tracking-widest text-red-600 mb-3">HARGA</div>
                <h2 class="font-display text-3xl sm:text-4xl text-neutral-900 mb-3">Transparan, tidak ada biaya tersembunyi</h2>
                <p class="text-neutral-600">Harga menyesuaikan hari dan jam main secara otomatis.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <!-- Standar -->
                <div class="rounded-2xl border border-neutral-200 overflow-hidden bg-white">
                    <div class="p-5 border-b border-neutral-200">
                        <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold uppercase tracking-wider mb-2">Standar</span>
                        <p class="text-sm text-neutral-500">5 lapangan • sintetis dan vinyl • indoor dan outdoor</p>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekday</div>
                                <div class="text-xs text-neutral-500">08.00 – 15.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">Rp 60.000/jam</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekday</div>
                                <div class="text-xs text-neutral-500">15.00 – 21.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">Rp 100.000/jam</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekend / Tanggal merah</div>
                                <div class="text-xs text-neutral-500">08.00 – 15.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">Rp 80.000/jam</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekend / Tanggal merah</div>
                                <div class="text-xs text-neutral-500">15.00 – 21.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">Rp 130.000/jam</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Internasional -->
                <div class="rounded-2xl border border-neutral-200 overflow-hidden bg-white">
                    <div class="p-5 border-b border-neutral-200">
                        <span class="inline-block px-3 py-1 rounded-full bg-pink-100 text-pink-700 text-xs font-bold uppercase tracking-wider mb-2">Internasional</span>
                        <p class="text-sm text-neutral-500">4 lapangan • sintetis dan vinyl • indoor dan outdoor</p>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekday</div>
                                <div class="text-xs text-neutral-500">08.00 – 15.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-red-600">Rp 80.000/jam</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekday</div>
                                <div class="text-xs text-neutral-500">15.00 – 21.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-red-600">Rp 120.000/jam</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekend / Tanggal merah</div>
                                <div class="text-xs text-neutral-500">08.00 – 15.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-red-600">Rp 100.000/jam</div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-semibold text-neutral-800">Weekend / Tanggal merah</div>
                                <div class="text-xs text-neutral-500">15.00 – 21.00</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-red-600">Rp 150.000/jam</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-neutral-500 mt-6">* Jenis permukaan (sintetis/vinyl) dan tipe area (indoor/outdoor) tidak mempengaruhi harga — murni preferensi kamu.</p>
        </div>
    </section>



    <!-- ===== FOOTER ===== -->
    <footer id="kontak" class="border-t border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 grid sm:grid-cols-2 md:grid-cols-3 gap-10">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-600"></span>
                    <span class="font-display text-lg text-neutral-900">FutsalKite</span>
                </div>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Mall Cengkareng, Lantai 9<br>
                    Jakarta Barat, DKI Jakarta
                </p>
            </div>

            <div>
                <div class="font-bold text-sm text-neutral-900 mb-3">Platform</div>
                <ul class="space-y-2 text-sm text-neutral-500">
                    <li><a href="#lapangan" class="hover:text-neutral-900">Lapangan</a></li>
                    <li><a href="#tarif" class="hover:text-neutral-900">Tarif</a></li>
                    <li><a href="{{ route('login', ['mode' => 'register']) }}" class="hover:text-neutral-900">Daftar Akun</a></li>
                </ul>
            </div>

            <div>
                <div class="font-bold text-sm text-neutral-900 mb-3">Bantuan</div>
                <ul class="space-y-2 text-sm text-neutral-500">
                    <li><a href="https://wa.me/62895610031040" target="_blank" rel="noopener" class="hover:text-neutral-900">Kontak</a></li>
                    <li><a href="{{ route('syarat-ketentuan') }}" class="hover:text-neutral-900">Syarat &amp; Ketentuan</a></li>
                    <li><a href="{{ route('kebijakan-privasi') }}" class="hover:text-neutral-900">Kebijakan Privasi</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-neutral-200 py-6 text-center text-xs text-neutral-400">
            &copy; {{ date('Y') }} FutsalKite. Semua hak dilindungi.
        </div>
    </footer>

</body>
</html>
