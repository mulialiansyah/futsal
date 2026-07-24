<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Futsal Booking') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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
            font-family: sans-serif;
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
<body class="font-sans antialiased bg-gray-50"
      x-data="{ isLoading: false }"
      @loading-start="isLoading = true"
      @loading-end="isLoading = false">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('customer.booking.index') }}" class="flex items-center gap-2">
                        <span class="text-2xl font-extrabold text-green-600">⚽ Futsal</span>
                    </a>

                    <!-- Menu -->
                    <div class="flex items-center gap-6">
                        <a href="{{ route('customer.booking.index') }}" 
                           class="text-sm font-medium text-gray-700 hover:text-green-600 transition">
                            Booking Saya
                        </a>
                        <a href="{{ route('customer.booking.create') }}" 
                           class="text-sm font-medium text-gray-700 hover:text-green-600 transition">
                            Booking Baru
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm font-medium text-gray-700 hover:text-red-600 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                @if ($message = Session::get('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ $message }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 py-6 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
                <p>&copy; 2026 Futsal Booking. All rights reserved.</p>
            </div>
        </footer>
    </div>

<x-loader label="Memuat data..." />
<x-confirm-modal />

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

</body>
</html>
