<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FutsalKIte - Masuk</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">
    <!-- AlpineJS dari CDN agar pasti ter-load -->
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

        /* ====== LEFT SIDE - GAMBAR ====== */
        .left-panel {
            display: none;
            position: relative;
            width: 50%;
            background: #05050a;
            isolation: isolate;
        }
        @media (min-width: 768px) {
            .left-panel { display: block; }
        }

        .left-panel img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 30%;
            transform: scale(1.04);
            filter: saturate(1.08) contrast(1.05);
        }

        /* Layer 1: menggelapkan atas & bawah supaya teks kebaca, tengah dibiarkan lebih terang */
        .left-panel .overlay-grad {
            position: absolute;
            inset: 0;
            z-index: 1;
            background:
                linear-gradient(to top, rgba(4,4,8,0.92) 0%, rgba(4,4,8,0.15) 42%, rgba(4,4,8,0.05) 60%, rgba(4,4,8,0.55) 100%);
        }

        /* Layer 2: vignette halus di tepi biar nggak terasa "kotak nempel" ke panel putih */
        .left-panel .overlay-vignette {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: radial-gradient(120% 100% at 30% 40%, transparent 55%, rgba(0,0,0,0.55) 100%);
            mix-blend-mode: multiply;
        }

        /* Layer 3: sapuan warna hangat tipis biar nyatu sama nuansa api di foto */
        .left-panel .overlay-tint {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: linear-gradient(200deg, rgba(232,65,44,0.10), transparent 45%);
            pointer-events: none;
        }

        /* Partikel ambient */
        .left-panel canvas.dust {
            position: absolute;
            inset: 0;
            z-index: 3;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        /* Badge info kecil di atas */
        .left-panel .top-badge {
            position: absolute;
            top: 28px;
            left: 28px;
            z-index: 4;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            backdrop-filter: blur(6px);
            color: #f3f1ea;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .left-panel .top-badge .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #fbbf24;
            box-shadow: 0 0 8px 2px rgba(251,191,36,0.7);
        }

        .left-panel .brand {
            position: absolute;
            bottom: 44px;
            left: 40px;
            right: 40px;
            z-index: 4;
            color: #fff;
            animation: fadeUp 0.9s cubic-bezier(.2,.8,.2,1) both;
        }
        .left-panel .brand h1 {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 2.6rem;
            letter-spacing: 1.5px;
            line-height: 1;
            background: linear-gradient(180deg, #ffe8a3 0%, #fbbf24 55%, #e8412c 120%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            filter: drop-shadow(0 4px 18px rgba(0,0,0,0.55));
            margin-bottom: 8px;
        }
        .left-panel .brand p {
            font-size: 0.88rem;
            color: #e6e3da;
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
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

        /* Animasi transisi */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

<div x-data="{ isLogin: {{ (request('mode') === 'register' || ($errors->has('email') && !request()->routeIs('login'))) ? 'false' : 'true'}} }" class="card">

    <!-- LEFT: Image Panel -->
    <div class="left-panel">
        <img src="{{ asset('images/bg-login.jpg') }}"
             onerror="this.src='https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=870&auto=format&fit=crop'"
             alt="FutsalKIte Illustration">
        <div class="overlay-grad"></div>
        <div class="overlay-vignette"></div>
        <div class="overlay-tint"></div>
        <canvas class="dust" id="dust"></canvas>

        <div class="top-badge"><span class="dot"></span>9 Lapangan &middot; Booking Real-time</div>

        <div class="brand">
            <h1>FutsalKIte</h1>
            <p>Platform Booking Lapangan Futsal Terbaik</p>
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

            <!-- Session Status -->
            @if (session('status'))
                <div style="background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;font-size:0.83rem;font-weight:600;margin-bottom:1rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
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
                    <button type="button" class="toggle-btn" @click="isLogin = false">Daftar gratis</button>
                </div>
            </form>
        </div>

        <!-- ===== REGISTER FORM ===== -->
        <div x-show="!isLogin" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">

            <div class="form-title">BUAT AKUN</div>
            <div class="form-subtitle">Gabung <span>FutsalKIte</span> hari ini — gratis!</div>

            <form method="POST" action="{{ route('register') }}">
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
                    <button type="button" class="toggle-btn" @click="isLogin = true">Masuk di sini</button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    (function () {
        var canvas = document.getElementById('dust');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var w, h, particles;

        function resize() {
            w = canvas.width = canvas.offsetWidth;
            h = canvas.height = canvas.offsetHeight;
        }

        function initParticles() {
            particles = Array.from({ length: 40 }, function () {
                return {
                    x: Math.random() * w,
                    y: Math.random() * h,
                    r: Math.random() * 1.5 + 0.4,
                    vx: (Math.random() - 0.5) * 0.12,
                    vy: -Math.random() * 0.22 - 0.04,
                    o: Math.random() * 0.5 + 0.15
                };
            });
        }

        function animate() {
            ctx.clearRect(0, 0, w, h);
            particles.forEach(function (p) {
                p.x += p.vx; p.y += p.vy;
                if (p.y < -10) { p.y = h + 10; p.x = Math.random() * w; }
                if (p.x < -10) p.x = w + 10;
                if (p.x > w + 10) p.x = -10;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(251,191,36,' + p.o + ')';
                ctx.fill();
            });
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