<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FutsalKIte') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .font-display { font-family: 'Anton', sans-serif; letter-spacing: 0.5px; }
    </style>
</head>
<body class="bg-neutral-950 text-white">

<div class="flex min-h-screen bg-neutral-950 flex-col">

    <!-- TOPBAR -->
    <header class="sticky top-0 z-50 relative bg-neutral-950/95 backdrop-blur-md border-b border-white/10 overflow-hidden">
        <!-- ambient glow -->
        <div class="pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-24 left-10 w-72 h-72 rounded-full bg-red-600/20 blur-3xl"></div>
            <div class="absolute -top-24 right-24 w-72 h-72 rounded-full bg-amber-400/10 blur-3xl"></div>
        </div>
        
        <div class="relative px-4 py-4 flex items-center justify-between max-w-6xl mx-auto">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_2px_rgba(239,68,68,0.7)]"></span>
                <span class="font-display text-xl text-white">FutsalKIte</span>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                </button>
                <div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-white/10 border border-white/20">
                    <div class="w-8 h-8 rounded-full bg-sky-400/30 flex items-center justify-center text-base">👨</div>
                    <div class="hidden sm:block">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-neutral-400">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-neutral-400 hover:text-red-400 transition" aria-label="Keluar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 min-w-0 flex flex-col">
        <!-- PAGE CONTENT -->
        <div class="flex-1 px-4 py-6 pb-36">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-2xl font-bold mb-1">Halo, {{ Auth::user()->name }} 👋</h1>
                <p class="text-sm text-neutral-400 mb-6">Kelola lapangan futsal kamu hari ini</p>
                
                <!-- Main Slot -->
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- BOTTOM NAV (ALL DEVICES) -->
        <nav class="fixed bottom-0 left-0 right-0 bg-neutral-950/95 backdrop-blur-md border-t border-white/10 z-50 px-4 py-3">
            <div class="max-w-md mx-auto flex items-center justify-around">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.dashboard') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Beranda</span>
                </a>
                <a href="{{ route('admin.lapangan.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.lapangan.*') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.lapangan.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Lapangan</span>
                </a>
                <a href="{{ route('admin.tarif.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.tarif.*') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.tarif.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Tarif</span>
                </a>
                <a href="{{ route('admin.booking.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.booking.*') ? 'text-amber-400' : 'text-neutral-500' }} relative">
                    <div class="{{ request()->routeIs('admin.booking.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9-3.582 9-8z"/></svg>
                    </div>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)] rounded-full"></span>
                    <span class="text-xs font-semibold">Booking</span>
                </a>
                <a href="{{ route('admin.laporan.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.laporan.*') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.laporan.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2v14a2 2 0 00-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Pendapatan</span>
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.pembayaran.*') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.pembayaran.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Pembayaran</span>
                </a>
            </div>
        </nav>
    </main>
</div>

</body>
</html>