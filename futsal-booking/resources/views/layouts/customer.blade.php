<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FutsalPro – Booking Lapangan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    <!-- Navbar -->
    <nav class="bg-green-700 text-white shadow-lg">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('customer.home') }}" class="text-2xl font-extrabold tracking-tight">
                ⚽ FutsalPro
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('customer.home') }}"
                   class="text-sm font-medium hover:text-green-200 transition {{ request()->routeIs('customer.home') ? 'underline underline-offset-4' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('customer.booking.index') }}"
                   class="text-sm font-medium hover:text-green-200 transition {{ request()->routeIs('customer.booking.*') ? 'underline underline-offset-4' : '' }}">
                    Booking Saya
                </a>
                <a href="{{ route('customer.booking.create') }}"
                   class="bg-white text-green-700 font-bold text-sm px-4 py-2 rounded-lg hover:bg-green-50 transition">
                    + Booking Lapangan
                </a>
                <div class="text-sm text-green-200">
                    Hi, <span class="font-semibold text-white">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-300 hover:text-white transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
