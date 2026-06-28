<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Futsal Booking') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
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
</body>
</html>