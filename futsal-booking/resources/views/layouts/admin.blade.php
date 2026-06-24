<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel – FutsalPro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-green-800 text-white flex flex-col fixed h-full shadow-xl">
            <div class="px-6 py-5 border-b border-green-700">
                <h1 class="text-2xl font-extrabold tracking-tight">⚽ FutsalPro</h1>
                <p class="text-green-300 text-sm mt-1">Admin Panel</p>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 text-white' : 'text-green-200 hover:bg-green-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.lapangan.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('admin.lapangan.*') ? 'bg-green-600 text-white' : 'text-green-200 hover:bg-green-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kelola Lapangan
                </a>
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('admin.bookings.*') ? 'bg-green-600 text-white' : 'text-green-200 hover:bg-green-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Semua Booking
                </a>
                <a href="{{ route('admin.pembayaran.index') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-medium transition {{ request()->routeIs('admin.pembayaran.*') ? 'bg-green-600 text-white' : 'text-green-200 hover:bg-green-700' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verifikasi DP
                </a>
            </nav>
            <div class="px-4 py-4 border-t border-green-700">
                <p class="text-green-300 text-sm mb-2">Login sebagai:</p>
                <p class="font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-red-300 hover:text-red-100 transition">
                        → Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="ml-64 flex-1 flex flex-col">
            <header class="bg-white shadow px-8 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-700">@yield('title', 'Dashboard')</h2>
                <span class="text-sm text-gray-500">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            </header>

            <main class="flex-1 p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
