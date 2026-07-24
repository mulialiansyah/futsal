@php
    $unreadNotificationsCount = auth()->check() ? auth()->user()->notifikasis()->where('is_read', false)->count() : 0;
    $latestNotifications = auth()->check() ? auth()->user()->notifikasis()->latest()->take(10)->get() : collect();
@endphp

<!-- ===== DESKTOP / TABLET SIDEBAR (Hidden on Mobile) -->
<aside class="hidden md:flex flex-col w-64 fixed inset-y-0 left-0 z-40 bg-neutral-950/95 backdrop-blur-md border-r border-white/10 p-5 overflow-y-auto justify-between">
    <div class="space-y-6">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 px-2 py-1">
            <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_12px_3px_rgba(239,68,68,0.8)]"></span>
            <span class="font-display text-2xl text-white tracking-wide">FutsalKite</span>
        </a>

        <!-- Navigation Links -->
        <nav class="space-y-1.5">
            <p class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest px-3 mb-2">Menu Utama</p>

            <a href="{{ url('/') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->is('/') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="{{ route('customer.booking.create') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('customer.booking.create') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Booking Lapangan</span>
            </a>

            <a href="{{ route('customer.lapangan.denah') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('customer.lapangan.denah') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                <span>Denah Lapangan</span>
            </a>

            <a href="{{ route('customer.booking.index') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('customer.booking.index') || request()->routeIs('customer.booking.show') ? 'bg-amber-400/15 text-amber-400 border border-amber-400/30' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Riwayat Booking</span>
                </div>
                @if($unreadNotificationsCount > 0)
                    <span class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.8)]"></span>
                @endif
            </a>

            <a href="https://wa.me/62895610031040" target="_blank" rel="noopener"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-semibold text-sm text-neutral-300 hover:bg-white/5 hover:text-white transition-all">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Chat Admin WA</span>
            </a>
        </nav>
    </div>

    <!-- Sidebar User Footer -->
    @auth
        @include('partials.sidebar-user-card')
    @endauth
</aside>

<!-- ===== TOP BAR ===== -->
<header class="football-topbar sticky top-0 z-30 overflow-visible backdrop-blur-md border-b border-white/10 md:pl-64"
        style="background-color: #080808; overflow: visible;">
    <img src="{{ asset('images/navbar-football-monochrome.png') }}" alt="" aria-hidden="true"
         class="pointer-events-none absolute inset-y-0 right-0 h-full w-80 object-cover object-right opacity-90">
    <div class="pointer-events-none absolute inset-y-0 right-0 w-96 bg-gradient-to-l from-transparent via-black/35 to-black"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo (Mobile only) -->
        <a href="{{ url('/') }}" class="flex items-center gap-2 md:hidden">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_2px_rgba(239,68,68,0.7)]"></span>
            <span class="font-display text-lg text-white">FutsalKite</span>
        </a>

        <!-- Desktop Page Indicator -->
        <div class="hidden md:flex items-center gap-2">
            <span class="text-sm font-semibold text-neutral-400">Area Customer</span>
            <span class="text-neutral-600">/</span>
            <span class="text-sm font-bold text-amber-400">
                @if(request()->is('/')) Beranda
                @elseif(request()->routeIs('customer.booking.create')) Booking Lapangan
                @elseif(request()->routeIs('customer.lapangan.denah')) Denah Lapangan
                @elseif(request()->routeIs('customer.booking.index') || request()->routeIs('customer.booking.show')) Riwayat Booking
                @elseif(request()->routeIs('profile.edit')) Profil Saya
                @else Portal @endif
            </span>
        </div>

        <div class="flex items-center gap-4 ml-auto">
            <!-- Notifikasi Bell -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="relative w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-neutral-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.65V5a2 2 0 10-4 0v.35A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($unreadNotificationsCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_8px_1px_rgba(239,68,68,0.8)]"></span>
                    @endif
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute right-0 mt-3 w-80 rounded-2xl bg-neutral-900 border border-white/10 shadow-2xl overflow-hidden z-50">
                    <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center bg-neutral-950">
                        <span class="font-bold text-sm text-white">Notifikasi</span>
                        @if($unreadNotificationsCount > 0)
                            <form action="{{ route('customer.notifikasi.readAll') }}" method="POST" class="inline m-0 p-0">
                                @csrf
                                <button type="submit" class="text-[11px] text-amber-400 hover:underline font-semibold bg-transparent border-0 p-0 cursor-pointer">
                                    Tandai semua dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-white/5">
                        @forelse($latestNotifications as $notif)
                            <div class="px-4 py-3 hover:bg-white/5 transition relative {{ !$notif->is_read ? 'bg-white/[0.02]' : '' }}">
                                <div class="flex justify-between items-start gap-2">
                                    <p class="text-sm font-semibold {{ !$notif->is_read ? 'text-white font-bold' : 'text-neutral-300' }}">
                                        {{ $notif->judul }}
                                    </p>
                                    @if(!$notif->is_read)
                                        <form action="{{ route('customer.notifikasi.read', $notif) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-amber-400 hover:text-amber-300 bg-transparent border-0 p-0 cursor-pointer" title="Tandai dibaca">
                                                ✓
                                            </button>
                                        </form>
                                    @endif
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
                        @empty
                            <div class="px-4 py-8 text-center text-neutral-500 text-sm">
                                <span class="text-2xl mb-1 block">📭</span>
                                Belum ada notifikasi baru.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Profile & Logout (Mobile Avatar Badge) -->
            @auth
            <div class="flex items-center gap-3 md:hidden">
                <a href="{{ route('profile.edit') }}" class="w-8 h-8 rounded-full bg-red-600/20 border border-red-500/30 text-red-400 font-bold flex items-center justify-center text-xs hover:bg-red-600/30 transition">
                    {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-neutral-400 hover:text-white transition p-1" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</header>

<!-- ===== BOTTOM NAV (Mobile Only - Hidden on Desktop & Tablet: md:hidden) ===== -->
<nav class="fixed bottom-0 left-0 right-0 z-40 bg-neutral-950/95 backdrop-blur-md border-t border-white/10 md:hidden pb-safe">
    <div class="max-w-5xl mx-auto grid grid-cols-5 text-center">
        <!-- Beranda -->
        <a href="{{ url('/') }}" class="flex flex-col items-center gap-1 py-3 transition {{ request()->is('/') ? 'text-amber-400' : 'text-neutral-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h4v-6h4v6h4a1 1 0 001-1V10"/>
            </svg>
            <span class="text-[10px] font-semibold">Beranda</span>
        </a>
        <!-- Booking -->
        <a href="{{ route('customer.booking.create') }}" class="flex flex-col items-center gap-1 py-3 transition {{ request()->routeIs('customer.booking.create') ? 'text-amber-400' : 'text-neutral-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-[10px] font-bold">Booking</span>
        </a>
        <!-- Riwayat -->
        <a href="{{ route('customer.booking.index') }}" class="flex flex-col items-center gap-1 py-3 transition {{ request()->routeIs('customer.booking.index') || request()->routeIs('customer.booking.show') ? 'text-amber-400' : 'text-neutral-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-[10px] font-semibold">Riwayat</span>
        </a>
        <!-- Chat -->
        <a href="https://wa.me/62895610031040" target="_blank" rel="noopener" class="flex flex-col items-center gap-1 py-3 text-neutral-400 hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.9 7.9 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="text-[10px] font-semibold">Chat</span>
        </a>
        <!-- Profil -->
        <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-1 py-3 transition {{ request()->routeIs('profile.edit') ? 'text-amber-400' : 'text-neutral-400 hover:text-white' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span class="text-[10px] font-semibold">Profil</span>
        </a>
    </div>
</nav>
