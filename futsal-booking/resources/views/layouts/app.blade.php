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
    </style>
</head>
<body class="font-sans antialiased bg-neutral-950 text-white">

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

</body>
</html>
