<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .font-display { font-family: 'Anton', sans-serif; letter-spacing: 0.5px; }

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
            font-family: 'Figtree', sans-serif;
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
    </style>
</head>
<body class="font-sans antialiased bg-neutral-950 text-white"
      x-data="{ isLoading: false }"
      @loading-start="isLoading = true"
      @loading-end="isLoading = false">

<div class="flex min-h-screen bg-neutral-950 flex-col">
    <!-- Ambient Glow -->
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-48 -left-24 w-96 h-96 rounded-full bg-red-600/20 blur-3xl"></div>
        <div class="absolute -top-48 -right-24 w-96 h-96 rounded-full bg-amber-400/10 blur-3xl"></div>
    </div>

    @include('layouts.navigation')

    <!-- MAIN CONTENT CONTAINER (Offset md:pl-64) -->
    <main class="flex-1 min-w-0 flex flex-col md:pl-64">
        <!-- PAGE CONTENT -->
        <div class="flex-1 px-4 py-6 pb-36 md:pb-12">
            <div class="max-w-6xl mx-auto">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>

<x-loader label="Memuat data..." />

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
