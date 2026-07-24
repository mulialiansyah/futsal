<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FutsalKite — Akses Akun</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=barlow:400,500,600,700,800|barlow-condensed:600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#090b10] font-['Barlow'] text-slate-100 antialiased">
        <main class="relative isolate flex min-h-screen items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
            <div class="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_top_left,_rgba(239,68,68,0.28),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(245,158,11,0.2),_transparent_36%)]"></div>
            <div class="absolute -left-24 top-1/4 -z-10 h-72 w-72 rounded-full border border-white/10"></div>
            <div class="absolute -right-20 bottom-10 -z-10 h-80 w-80 rounded-full border border-white/10"></div>

            <section class="w-full max-w-md">
                <a href="{{ url('/') }}" class="mb-7 flex items-center justify-center gap-3 text-center">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-orange-500 text-2xl shadow-lg shadow-red-950/50" aria-hidden="true">⚽</span>
                    <span class="text-left leading-none">
                        <span class="block font-['Barlow_Condensed'] text-3xl font-extrabold uppercase tracking-wide text-white">Futsal<span class="text-orange-400">Kite</span></span>
                        <span class="mt-1 block text-[10px] font-bold uppercase tracking-[0.22em] text-slate-400">Booking lapangan</span>
                    </span>
                </a>

                <div class="overflow-hidden rounded-3xl border border-white/10 bg-slate-950/80 shadow-2xl shadow-black/40 backdrop-blur">
                    <div class="h-1.5 bg-gradient-to-r from-red-500 via-orange-400 to-amber-300"></div>
                    <div class="px-6 py-7 sm:px-8 sm:py-9">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-6 text-center text-xs text-slate-500">© {{ now()->year }} FutsalKite · Platform booking lapangan futsal</p>
            </section>
        </main>
    </body>
</html>
