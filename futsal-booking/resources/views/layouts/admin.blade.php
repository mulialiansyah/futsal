@php
    $unreadNotificationsCount = auth()->check() ? auth()->user()->notifikasis()->where('is_read', false)->count() : 0;
    $latestNotifications = auth()->check() ? auth()->user()->notifikasis()->latest()->take(10)->get() : collect();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FutsalKite') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&family=Anton&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .font-display { font-family: 'Anton', sans-serif; letter-spacing: 0.5px; }
        [x-cloak] { display: none !important; }

        /* Navigasi bawah admin: dua baris di mobile agar delapan menu tidak saling berhimpitan. */
        @media (max-width: 767px) {
            .admin-mobile-nav-list {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 0.25rem 0.5rem;
            }

            .admin-mobile-nav-list > a {
                min-width: 0;
                text-align: center;
            }

            .admin-mobile-nav-list > a > span:last-child {
                display: flex;
                min-height: 1.75rem;
                align-items: center;
                justify-content: center;
                font-size: 0.625rem;
                line-height: 0.75rem;
                overflow-wrap: anywhere;
            }
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
<body class="bg-neutral-950 text-white"
      x-data="{ isLoading: false }"
      @loading-start="isLoading = true"
      @loading-end="isLoading = false">

<div class="flex min-h-screen bg-neutral-950 flex-col">

    <!-- DESKTOP / TABLET SIDEBAR (Hidden on Mobile) -->
    <aside class="hidden md:flex flex-col w-64 fixed inset-y-0 left-0 z-40 bg-neutral-950/95 backdrop-blur-md border-r border-white/10 p-5 overflow-y-auto justify-between">
        <div class="space-y-6">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-2 py-1">
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_12px_3px_rgba(239,68,68,0.8)]"></span>
                <span class="font-display text-2xl text-white tracking-wide">FutsalKite</span>
                <span class="text-[10px] font-extrabold uppercase bg-red-500/20 text-red-400 px-2 py-0.5 rounded-full border border-red-500/30 ml-auto">Admin</span>
            </a>

            <!-- Navigation Links -->
            <nav class="space-y-1.5">
                <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest px-3 mb-2">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('admin.lapangan.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.lapangan.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    <span>Lapangan</span>
                </a>

                <a href="{{ route('admin.tarif.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.tarif.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 10v2"/></svg>
                    <span>Tarif</span>
                </a>

                <a href="{{ route('admin.booking.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.booking.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9-3.582 9-8z"/></svg>
                        <span>Booking</span>
                    </div>
                    @if($unreadNotificationsCount > 0)
                        <span class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                    @endif
                </a>

                <a href="{{ route('admin.laporan.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2v14a2 2 0 00-2 2z"/></svg>
                    <span>Pendapatan</span>
                </a>

                <a href="{{ route('admin.pembayaran.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.pembayaran.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Pembayaran</span>
                </a>

                <a href="{{ route('admin.hari-libur.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.hari-libur.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Hari Libur</span>
                </a>

                <a href="{{ route('admin.ketersediaan.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('admin.ketersediaan.*') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Ketersediaan</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar User Footer -->
        @include('partials.sidebar-user-card')
    </aside>

    <!-- TOPBAR (Mobile Header & Desktop Header Offset) -->
    <header class="football-topbar sticky top-0 z-30 relative overflow-visible backdrop-blur-md border-b border-white/10 md:pl-64"
            style="background-color: #080808; overflow: visible;">
        <img src="{{ asset('images/navbar-football-monochrome.png') }}" alt="" aria-hidden="true"
             class="pointer-events-none absolute inset-y-0 right-0 h-full w-80 object-cover object-right opacity-90">
        <div class="pointer-events-none absolute inset-y-0 right-0 w-96 bg-gradient-to-l from-transparent via-black/35 to-black"></div>
        <!-- ambient glow -->
        <div class="hidden pointer-events-none absolute inset-0 -z-10">
            <div class="absolute -top-24 left-10 w-72 h-72 rounded-full bg-red-600/20 blur-3xl"></div>
            <div class="absolute -top-24 right-24 w-72 h-72 rounded-full bg-amber-400/10 blur-3xl"></div>
        </div>
        
        <div class="relative z-10 px-4 py-3.5 flex items-center justify-between max-w-6xl mx-auto">
            <!-- Mobile Logo (Hidden on Desktop/Tablet) -->
            <div class="flex items-center gap-2 md:hidden">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_2px_rgba(239,68,68,0.7)]"></span>
                <span class="font-display text-xl text-white">FutsalKite</span>
            </div>

            <!-- Desktop Title indicator (Hidden on Mobile) -->
            <div class="hidden md:flex items-center gap-2">
                <span class="text-sm font-semibold text-neutral-400">Admin Dashboard</span>
                <span class="text-neutral-600">/</span>
                <span class="text-sm font-bold text-amber-400">
                    @if(request()->routeIs('admin.dashboard')) Beranda
                    @elseif(request()->routeIs('admin.lapangan.*')) Kelola Lapangan
                    @elseif(request()->routeIs('admin.tarif.*')) Kelola Tarif
                    @elseif(request()->routeIs('admin.booking.*')) Kelola Booking
                    @elseif(request()->routeIs('admin.laporan.*')) Laporan Pendapatan
                    @elseif(request()->routeIs('admin.pembayaran.*')) Verifikasi Pembayaran
                    @elseif(request()->routeIs('admin.hari-libur.*')) Hari Libur
                    @elseif(request()->routeIs('admin.ketersediaan.*')) Ketersediaan
                    @else Control Panel @endif
                </span>
            </div>

            <div class="flex items-center gap-3 ml-auto">
                <!-- Notifikasi Bell Admin -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center hover:bg-white/20 transition">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadNotificationsCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                        @endif
                    </button>

                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-3 w-80 rounded-2xl bg-neutral-900 border border-white/10 shadow-2xl overflow-hidden z-50">
                        <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center bg-neutral-950">
                            <span class="font-bold text-sm text-white">Notifikasi Admin</span>
                            @if($unreadNotificationsCount > 0)
                                <form action="{{ route('admin.notifikasi.readAll') }}" method="POST" class="inline m-0 p-0">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-amber-400 hover:underline font-semibold bg-transparent border-0 p-0 cursor-pointer">
                                        Tandai semua dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto divide-y divide-white/5">
                            @forelse($latestNotifications as $notif)
                                @if(!$notif->is_read)
                                    <form action="{{ route('admin.notifikasi.read', $notif) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 hover:bg-white/5 transition relative bg-white/[0.02] cursor-pointer">
                                            <div class="flex justify-between items-start gap-2">
                                                <p class="text-sm font-semibold text-white font-bold">
                                                    {{ $notif->judul }}
                                                </p>
                                                <span class="text-[10px] text-amber-400 hover:text-amber-300">✓</span>
                                            </div>
                                            <p class="text-xs text-neutral-400 mt-1 leading-relaxed">{{ $notif->pesan }}</p>
                                            <div class="flex items-center justify-between mt-1.5">
                                                <span class="text-[10px] text-neutral-500">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </span>
                                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-white/10 text-neutral-400 uppercase tracking-wider font-semibold">
                                                    {{ $notif->tipe }}
                                                </span>
                                            </div>
                                        </button>
                                    </form>
                                @else
                                    <div class="px-4 py-3 hover:bg-white/5 transition relative">
                                        <div class="flex justify-between items-start gap-2">
                                            <p class="text-sm font-semibold text-neutral-300">
                                                {{ $notif->judul }}
                                            </p>
                                        </div>
                                        <p class="text-xs text-neutral-400 mt-1 leading-relaxed">{{ $notif->pesan }}</p>
                                        <div class="flex items-center justify-between mt-1.5">
                                            <span class="text-[10px] text-neutral-500">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </span>
                                            <span class="text-[9px] px-1.5 py-0.5 rounded bg-white/10 text-neutral-400 uppercase tracking-wider font-semibold">
                                                {{ $notif->tipe }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="px-4 py-8 text-center text-neutral-500 text-sm">
                                    <span class="text-2xl mb-1 block">📭</span>
                                    Belum ada notifikasi baru.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Mobile Profile Header Badge -->
                <div class="flex items-center gap-3 px-3 py-1.5 rounded-xl bg-white/10 border border-white/20 md:hidden">
                    <div class="w-7 h-7 rounded-full bg-sky-400/30 flex items-center justify-center text-sm">👨</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-neutral-400 hover:text-red-400 transition" aria-label="Keluar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT CONTAINER (Offset md:pl-64) -->
    <main class="flex-1 min-w-0 flex flex-col md:pl-64">
        <!-- PAGE CONTENT -->
        <div class="flex-1 px-4 py-6 pb-36 md:pb-12">
            <div class="max-w-6xl mx-auto">
                <h1 class="text-2xl font-bold mb-1">Halo, {{ Auth::user()->name }}</h1>
                <p class="text-sm text-neutral-400 mb-6">Kelola lapangan futsal kamu hari ini</p>
                
                <!-- Main Slot -->
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>

        <!-- BOTTOM NAV (Mobile Only - Hidden on Desktop & Tablet: md:hidden) -->
        <nav class="admin-mobile-nav fixed bottom-0 left-0 right-0 z-50 border-t border-white/10 bg-neutral-950/95 px-3 py-2 backdrop-blur-md md:hidden">
            <div class="admin-mobile-nav-list max-w-md mx-auto">
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
                    @if($unreadNotificationsCount > 0)
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)] rounded-full"></span>
                    @endif
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
                <a href="{{ route('admin.hari-libur.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.hari-libur.*') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.hari-libur.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Hari Libur</span>
                </a>
                <a href="{{ route('admin.ketersediaan.index') }}" 
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.ketersediaan.*') ? 'text-amber-400' : 'text-neutral-500' }}">
                    <div class="{{ request()->routeIs('admin.ketersediaan.*') ? 'bg-amber-400/20' : '' }} p-2 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <span class="text-xs font-semibold">Ketersediaan</span>
                </a>
            </div>
        </nav>
    </main>
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
