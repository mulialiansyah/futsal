<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FutsalKite - Masuk</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Manrope', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .card {
            display: flex;
            width: 100%;
            max-width: 960px;
            min-height: 600px;
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.15);
        }

        /* ====== LEFT SIDE - POSTER SPORTY ====== */
        .left-panel {
            display: none;
            position: relative;
            width: 50%;
            background: #0a0a0a;
            isolation: isolate;
            overflow: hidden;
        }
        @media (min-width: 768px) {
            .left-panel { display: flex; flex-direction: column; }
        }

        .left-panel .photo-frame {
            position: absolute;
            top: 60px;    /* ruang untuk badge */
            bottom: 100px; /* ruang untuk brand */
            left: 0;
            right: 0;
            display: flex;
            align-items: stretch;
        }

        /* Strip kiri & kanan */
        .left-panel .strip {
            flex: 0 0 44px;
            background: #0a0a0a;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        /* Titik dekoratif */
        .strip-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
        }

        /* Foto memenuhi penuh area tengah */
        .left-panel .photo-wrap {
            flex: 1;
            position: relative;
            overflow: hidden;
        }
        .left-panel .photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 15%;
            filter: contrast(1.08) saturate(0.9);
        }

        /* Overlay gelap atas & bawah */
        .left-panel .photo-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                rgba(10,10,10,0.35) 0%,
                transparent 25%,
                transparent 60%,
                rgba(10,10,10,0.7) 100%);
            z-index: 1;
        }

        /* Brand bawah */
        .left-panel .brand {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 5;
            height: 100px;
            background: #0a0a0a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
        }
        .left-panel .brand h1 {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 2.2rem;
            letter-spacing: 2px;
            line-height: 1;
            color: #fff;
            margin-bottom: 5px;
        }
        .left-panel .brand p {
            font-size: 0.72rem;
            color: #aaa;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.5;
        }

        /* Badge atas */
        .left-panel .top-badge {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 5;
            height: 60px;
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #f0f0f0;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .left-panel .top-badge .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #f59e0b;
            box-shadow: 0 0 8px 2px rgba(245,158,11,0.8);
        }

        /* ====== GLITCH EFFECT ====== */
        .glitch-wrap {
            position: relative;
            display: inline-block;
        }
        .glitch-wrap h1 {
            position: relative;
            z-index: 1;
        }
        .glitch-wrap h1::before,
        .glitch-wrap h1::after {
            content: attr(data-text);
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            font-family: 'Anton', sans-serif;
            font-size: inherit;
            letter-spacing: inherit;
            color: #fff;
            opacity: 0;
        }
        .glitch-wrap h1::before {
            color: #0ff;
            animation: glitch-r 3.5s infinite;
            clip-path: polygon(0 20%, 100% 20%, 100% 40%, 0 40%);
        }
        .glitch-wrap h1::after {
            color: #f0f;
            animation: glitch-l 3.5s infinite;
            clip-path: polygon(0 60%, 100% 60%, 100% 80%, 0 80%);
        }
        @keyframes glitch-r {
            0%,90%,100% { opacity:0; transform: translate(0); }
            91%          { opacity:1; transform: translate(3px, -2px); }
            93%          { opacity:1; transform: translate(-3px, 2px); }
            95%          { opacity:1; transform: translate(2px, 1px); }
            97%          { opacity:0; }
        }
        @keyframes glitch-l {
            0%,90%,100% { opacity:0; transform: translate(0); }
            92%          { opacity:1; transform: translate(-3px, 2px); }
            94%          { opacity:1; transform: translate(3px, -1px); }
            96%          { opacity:1; transform: translate(-2px, 2px); }
            97%          { opacity:0; }
        }

        /* Scanline glitch di foto */
        .glitch-line {
            position: absolute;
            left: 0; right: 0;
            height: 2px;
            background: rgba(0, 255, 255, 0.25);
            z-index: 4;
            pointer-events: none;
            animation: scanline 6s linear infinite;
            mix-blend-mode: screen;
        }
        @keyframes scanline {
            0%   { top: 0%;   opacity: 0; }
            5%   { opacity: 1; }
            95%  { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        /* Glow border foto */
        .left-panel .photo-wrap {
            box-shadow: inset 0 0 30px rgba(0,255,255,0.08);
        }

        /* Canvas partikel */
        #particles-canvas {
            position: absolute;
            inset: 0;
            z-index: 3;
            pointer-events: none;
            width: 100%;
            height: 100%;
        }

        /* Glow pulse pada brand */
        .brand-glow {
            animation: brandPulse 3s ease-in-out infinite;
        }
        @keyframes brandPulse {
            0%,100% { text-shadow: 0 0 8px rgba(255,255,255,0.2); }
            50%      { text-shadow: 0 0 20px rgba(0,255,255,0.5), 0 0 40px rgba(0,255,255,0.2); }
        }

        /* RGB shift sesekali di foto */
        .photo-rgb {
            animation: rgbShift 5s infinite;
        }
        @keyframes rgbShift {
            0%,88%,100% { filter: contrast(1.08) saturate(0.9); }
            90%          { filter: contrast(1.08) saturate(0.9) hue-rotate(20deg); }
            92%          { filter: contrast(1.1) saturate(1.2) hue-rotate(-15deg); }
            94%          { filter: contrast(1.08) saturate(0.9); }
        }

        /* ====== RIGHT SIDE - FORM ====== */
        .right-panel {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.5rem;
        }
        @media (min-width: 768px) {
            .right-panel { width: 50%; padding: 3.5rem; }
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .form-subtitle {
            font-size: 0.85rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 2rem;
        }
        .form-subtitle span { color: #3b82f6; }

        label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Manrope', sans-serif;
            color: #111827;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }
        input:focus {
            border-color: #e8412c;
            box-shadow: 0 0 0 3px rgba(232,65,44,0.15);
        }
        .field { margin-bottom: 1rem; }

        .error-msg {
            font-size: 0.78rem;
            color: #ef4444;
            margin-top: 4px;
        }

        .row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: #4b5563;
            font-weight: 500;
            cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #e8412c;
        }
        .forgot-link {
            font-size: 0.82rem;
            font-weight: 700;
            color: #e8412c;
            text-decoration: none;
        }
        .forgot-link:hover { color: #b91c1c; }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: #e8412c;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 6px 20px rgba(232,65,44,0.4);
            margin-bottom: 1.25rem;
        }
        .btn-primary:hover {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(232,65,44,0.5);
        }
        .btn-primary:active { transform: translateY(0); }

        .center-text {
            text-align: center;
            font-size: 0.82rem;
            color: #6b7280;
            font-weight: 500;
            margin-top: 1.5rem;
        }
        .toggle-btn {
            background: none;
            border: none;
            color: #e8412c;
            font-weight: 700;
            font-size: 0.82rem;
            font-family: 'Manrope', sans-serif;
            cursor: pointer;
            padding: 0;
        }
        .toggle-btn:hover { text-decoration: underline; }

        /* ====== INTERACTIVE HOVER BUTTON ====== */
        .interactive-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            background: transparent;
            color: #e8412c;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: 'Manrope', sans-serif;
            border: 2px solid #e8412c;
            border-radius: 10px;
            cursor: pointer;
            overflow: hidden;
            transition: color 0.3s ease;
            min-width: 160px;
            height: 50px;
        }
        
        .interactive-btn .btn-text {
            position: relative;
            z-index: 2;
            transition: transform 0.4s cubic-bezier(0.65, 0, 0.35, 1), opacity 0.3s ease;
        }
        
        .interactive-btn .btn-text.original {
            position: absolute;
        }
        
        .interactive-btn .btn-text.new {
            position: absolute;
            transform: translateX(100%);
            opacity: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .interactive-btn .btn-text.new svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s ease;
        }
        
        .interactive-btn .dot-bg {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #e8412c;
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            z-index: 1;
            transition: transform 0.5s cubic-bezier(0.65, 0, 0.35, 1);
        }
        
        .interactive-btn:hover {
            color: #fff;
            border-color: #e8412c;
        }
        
        .interactive-btn:hover .dot-bg {
            transform: translate(-50%, -50%) scale(25);
        }
        
        .interactive-btn:hover .btn-text.original {
            transform: translateX(-100%);
            opacity: 0;
        }
        
        .interactive-btn:hover .btn-text.new {
            transform: translateX(0);
            opacity: 1;
        }
        
        .interactive-btn:hover .btn-text.new svg {
            transform: translateX(5px);
        }

        /* ====== KICKING SILHOUETTE LOADER ====== */
        .loader-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.95);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.8rem;
            z-index: 9999;
            font-family: 'Manrope', sans-serif;
        }

        .loader-stage {
            position: relative;
            width: 220px;
            height: 160px;
        }

        .loader-ground {
            position: absolute;
            bottom: 18px;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255,255,255,0.15);
        }

        .loader-figure {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-70px);
            width: 60px;
            height: 110px;
        }

        .loader-figure svg { width: 100%; height: 100%; display: block; }

        .loader-torso-group {
            transform-origin: 30px 55px;
            animation: loaderLean 0.9s ease-in-out infinite;
        }

        .loader-kick-leg {
            transform-origin: 26px 68px;
            animation: loaderKick 0.9s ease-in-out infinite;
        }

        .loader-plant-leg {
            transform-origin: 34px 68px;
        }

        @keyframes loaderLean {
            0%, 100% { transform: rotate(0deg); }
            35% { transform: rotate(-6deg); }
            50% { transform: rotate(4deg); }
        }

        @keyframes loaderKick {
            0%, 100% { transform: rotate(0deg); }
            30% { transform: rotate(-25deg); }
            50% { transform: rotate(55deg); }
            70% { transform: rotate(20deg); }
        }

        .loader-ball {
            position: absolute;
            bottom: 22px;
            left: 50%;
            width: 22px;
            height: 22px;
            margin-left: 8px;
            border-radius: 50%;
            background:
                radial-gradient(circle at 30% 25%, rgba(255,255,255,0.6), rgba(255,255,255,0) 45%),
                #f3f4f6;
            animation: loaderKicked 0.9s ease-in-out infinite;
        }

        .loader-ball::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background-image:
                radial-gradient(circle at 50% 20%, #111827 0 3.2px, transparent 4px),
                radial-gradient(circle at 22% 50%, #111827 0 2.8px, transparent 3.6px),
                radial-gradient(circle at 78% 50%, #111827 0 2.8px, transparent 3.6px),
                radial-gradient(circle at 35% 80%, #111827 0 2.8px, transparent 3.6px),
                radial-gradient(circle at 65% 80%, #111827 0 2.8px, transparent 3.6px);
        }

        @keyframes loaderKicked {
            0%, 45% { transform: translate(0, 0) scale(1); opacity: 1; }
            55% { transform: translate(70px, -18px) scale(0.85); opacity: 1; }
            65% { transform: translate(130px, 4px) scale(0.7); opacity: 0; }
            66%, 99% { opacity: 0; }
            100% { transform: translate(0, 0) scale(1); opacity: 0; }
        }

        .loader-label {
            font-size: 0.9rem;
            color: #e5e7eb;
            opacity: 0.6;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

<div x-data="{ isLogin: {{ (request('mode') === 'register' || ($errors->has('email') && !request()->routeIs('login'))) ? 'false' : 'true'}} }" class="card">

    <!-- LEFT: Poster Sporty Panel -->
    <div class="left-panel">
        <!-- Canvas partikel berterbangan -->
        <canvas id="particles-canvas"></canvas>

        <!-- Badge atas -->
        <div class="top-badge"><span class="dot"></span>9 Lapangan &middot; Booking Real-time</div>

        <!-- Frame: strip kiri | foto | strip kanan -->
        <div class="photo-frame">

            <!-- Strip kiri -->
            <div class="strip">
                <div class="strip-dot"></div>
                <div class="strip-dot"></div>
                <svg width="24" height="90" viewBox="0 0 24 90" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity:.8">
                    <polyline points="4,4 20,13 4,22"  stroke="white" stroke-width="2.5" stroke-linejoin="round" fill="none"/>
                    <polyline points="4,30 20,39 4,48" stroke="white" stroke-width="2.5" stroke-linejoin="round" fill="none"/>
                    <polyline points="4,56 20,65 4,74" stroke="white" stroke-width="2.5" stroke-linejoin="round" fill="none"/>
                </svg>
                <div class="strip-dot"></div>
                <div class="strip-dot"></div>
            </div>

            <!-- Foto tengah -->
            <div class="photo-wrap">
                <img class="photo-rgb"
                     src="{{ asset('images/bg-login.jpg') }}"
                     onerror="this.src='https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=870&auto=format&fit=crop'"
                     alt="FutsalKite">
                <!-- Scanline glitch -->
                <div class="glitch-line"></div>
            </div>

            <!-- Strip kanan -->
            <div class="strip">
                <div class="strip-dot"></div>
                <div class="strip-dot"></div>
                <svg width="24" height="90" viewBox="0 0 24 90" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity:.8">
                    <polyline points="4,4 20,13 4,22"  stroke="white" stroke-width="2.5" stroke-linejoin="round" fill="none"/>
                    <polyline points="4,30 20,39 4,48" stroke="white" stroke-width="2.5" stroke-linejoin="round" fill="none"/>
                    <polyline points="4,56 20,65 4,74" stroke="white" stroke-width="2.5" stroke-linejoin="round" fill="none"/>
                </svg>
                <div class="strip-dot"></div>
                <div class="strip-dot"></div>
            </div>
        </div>

        <!-- Brand bawah -->
        <div class="brand">
            <div class="glitch-wrap">
                <h1 class="brand-glow" data-text="FutsalKite">FutsalKite</h1>
            </div>
            <p>Platform Booking Lapangan<br>Futsal Terbaik</p>
        </div>
    </div>

    <!-- RIGHT: Form Panel -->
    <div class="right-panel">

        <!-- ===== LOGIN FORM ===== -->
        <div x-show="isLogin"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translateX(20px)"
             x-transition:enter-end="opacity-100 translateX(0)">

            <div class="form-title">SELAMAT DATANG KEMBALI</div>
            <div class="form-subtitle">Silakan masukkan detail Anda untuk melanjutkan</div>

            @if (session('status'))
                <div style="background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;font-size:0.83rem;font-weight:600;margin-bottom:1rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" @submit="$dispatch('loading-start')">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••">
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row-between">
                    <label class="remember-label">
                        <input type="checkbox" name="remember"> Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-primary">Masuk</button>

                <div class="center-text">
                    Belum punya akun?
                    <button type="button" class="interactive-btn" @click="isLogin = false">
                        <span class="dot-bg"></span>
                        <span class="btn-text original">Daftar gratis</span>
                        <span class="btn-text new">
                            Daftar sekarang
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- ===== REGISTER FORM ===== -->
        <div x-show="!isLogin" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">

            <div class="form-title">BUAT AKUN</div>
            <div class="form-subtitle">Gabung <span>FutsalKite</span> hari ini — gratis!</div>

            <form method="POST" action="{{ route('register') }}" @submit="$dispatch('loading-start')">
                @csrf

                <div class="field">
                    <label for="name">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama Anda">
                    @error('name')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="email_reg">Email</label>
                    <input id="email_reg" type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email Anda">
                    @error('email')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_reg">Password</label>
                    <input id="password_reg" type="password" name="password" required placeholder="••••••••">
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-primary">Daftar</button>

                <div class="center-text">
                    Sudah punya akun?
                    <button type="button" class="interactive-btn" @click="isLogin = true">
                        <span class="dot-bg"></span>
                        <span class="btn-text original">Masuk di sini</span>
                        <span class="btn-text new">
                            Masuk sekarang
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Loader Overlay -->
<div x-show="isLoading" x-cloak class="loader-overlay">
    <div class="loader-stage">
        <div class="loader-ground"></div>
        <div class="loader-figure">
            <svg viewBox="0 0 60 110">
                <g class="loader-torso-group" fill="#0b0f19">
                    <circle cx="30" cy="14" r="9" />
                    <path d="M20 24 Q30 20 40 24 L38 60 Q30 64 22 60 Z" />
                    <path d="M20 28 Q10 34 8 46 Q11 48 14 45 Q18 34 26 30 Z" />
                    <path d="M40 28 Q50 32 53 42 Q50 45 47 43 Q42 33 34 30 Z" />
                    <g class="loader-plant-leg">
                        <path d="M32 58 L38 100 Q34 104 29 102 L28 62 Z" />
                    </g>
                    <g class="loader-kick-leg">
                        <path d="M26 58 L20 96 Q25 101 30 98 L30 60 Z" />
                    </g>
                </g>
            </svg>
        </div>
        <div class="loader-ball"></div>
    </div>
    <p class="loader-label">Memuat data booking...</p>
</div>

<script>
    // Auto-trigger loading for all form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                window.dispatchEvent(new CustomEvent('loading-start'));
            });
        });
    });
</script>

<script>
(function () {
    var canvas = document.getElementById('particles-canvas');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var W, H, particles;

    function resize() {
        var panel = canvas.parentElement;
        W = canvas.width  = panel.offsetWidth;
        H = canvas.height = panel.offsetHeight;
    }

    // Tipe partikel: spark (titik), line (garis pendek), ring (lingkaran kecil)
    var TYPES = ['spark', 'spark', 'spark', 'line', 'ring'];
    var COLORS = [
        'rgba(0,255,255,',     // cyan
        'rgba(255,255,255,',   // putih
        'rgba(180,255,255,',   // cyan muda
        'rgba(255,100,200,',   // pink (glitch)
    ];

    function randColor() { return COLORS[Math.floor(Math.random() * COLORS.length)]; }

    function makeParticle() {
        return {
            x: Math.random() * W,
            y: Math.random() * H,
            type: TYPES[Math.floor(Math.random() * TYPES.length)],
            color: randColor(),
            r: Math.random() * 2 + 0.5,
            len: Math.random() * 18 + 6,
            angle: Math.random() * Math.PI * 2,
            vx: (Math.random() - 0.5) * 0.5,
            vy: -Math.random() * 0.7 - 0.1,
            o: Math.random() * 0.6 + 0.2,
            life: Math.random() * 200 + 100,
            age: 0,
        };
    }

    function initParticles() {
        particles = Array.from({ length: 55 }, makeParticle);
    }

    function drawParticle(p) {
        var fade = 1 - p.age / p.life;
        var alpha = p.o * fade;
        ctx.globalAlpha = alpha;

        if (p.type === 'spark') {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = p.color + alpha + ')';
            ctx.shadowBlur = 8;
            ctx.shadowColor = p.color + '0.8)';
            ctx.fill();

        } else if (p.type === 'line') {
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.x + Math.cos(p.angle) * p.len, p.y + Math.sin(p.angle) * p.len);
            ctx.strokeStyle = p.color + alpha + ')';
            ctx.lineWidth = 1;
            ctx.shadowBlur = 6;
            ctx.shadowColor = p.color + '0.6)';
            ctx.stroke();

        } else if (p.type === 'ring') {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r * 3, 0, Math.PI * 2);
            ctx.strokeStyle = p.color + alpha * 0.7 + ')';
            ctx.lineWidth = 0.8;
            ctx.shadowBlur = 10;
            ctx.shadowColor = p.color + '0.5)';
            ctx.stroke();
        }

        ctx.shadowBlur = 0;
        ctx.globalAlpha = 1;
    }

    function animate() {
        ctx.clearRect(0, 0, W, H);
        for (var i = 0; i < particles.length; i++) {
            var p = particles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.age++;
            // reset kalau sudah habis umur atau keluar layar
            if (p.age > p.life || p.y < -20 || p.x < -20 || p.x > W + 20) {
                particles[i] = makeParticle();
                particles[i].y = H + 10; // mulai dari bawah
            }
            drawParticle(p);
        }
        requestAnimationFrame(animate);
    }

    resize();
    initParticles();
    animate();
    window.addEventListener('resize', function () { resize(); initParticles(); });
})();
</script>
</body>
</html>
