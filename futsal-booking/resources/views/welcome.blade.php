<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FutsalKite</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 min-h-screen flex items-center justify-center p-4 sm:p-8">

    <div x-data="{ isLogin: true }" class="bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row w-full max-w-5xl min-h-[600px]">
        
        <!-- Left Side: Illustration Image -->
        <div class="hidden md:flex w-1/2 bg-black relative p-8 flex-col justify-center items-center">
            <!-- Menyesuaikan gambar dengan foto yang Anda berikan -->
            <img src="{{ asset('images/bg-login.jpg') }}" 
                 alt="Futsal Illustration" 
                 class="absolute inset-0 w-full h-full object-cover opacity-80" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            
            <div class="relative z-10 text-white mt-12 text-center">
                <h1 class="text-5xl font-extrabold tracking-tight mb-2 text-yellow-500 drop-shadow-lg">FutsalKite</h1>
                <p class="text-lg font-medium text-gray-200 drop-shadow-md">Platform Booking Lapangan Futsal Terbaik Anda.</p>
            </div>
        </div>

        <!-- Right Side: Form (Login / Register) -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center relative">
            
            <!-- ====== LOGIN FORM ====== -->
            <div x-show="isLogin" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">WELCOME BACK</h2>
                    <p class="text-sm text-gray-500 font-medium">Kindly enter your details to proceed</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="Enter your email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-500 shadow-sm focus:ring-red-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600 font-medium">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-red-500 hover:text-red-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-[#E53E3E] hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            Sign in
                        </button>
                    </div>

                    <!-- Google Sign In Button (Mock) -->
                    <div class="mt-4">
                        <button type="button" class="w-full bg-white border border-gray-300 text-gray-700 font-bold py-3 px-4 rounded-lg shadow-sm hover:bg-gray-50 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                <path fill="none" d="M1 1h22v22H1z"/>
                            </svg>
                            Sign in with Google
                        </button>
                    </div>

                    <p class="text-center text-sm text-gray-500 font-medium mt-8">
                        Don't have an account? 
                        <button type="button" @click="isLogin = false" class="text-red-500 font-bold hover:underline focus:outline-none">Sign up for free</button>
                    </p>
                </form>
            </div>

            <!-- ====== REGISTER FORM ====== -->
            <div x-show="!isLogin" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                <div class="mb-8 text-center md:text-left">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">CREATE ACCOUNT</h2>
                    <p class="text-sm text-gray-500 font-medium">Join FutsalKite today!</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="Enter your name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email_reg" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input id="email_reg" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="Enter your email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Phone Number (No. HP) -->
                    <div>
                        <label for="no_hp" class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                        <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="08xxxxxxxxxx">
                        <x-input-error :messages="$errors->get('no_hp')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password_reg" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                        <input id="password_reg" type="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all"
                               placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-[#E53E3E] hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                            Sign up
                        </button>
                    </div>

                    <p class="text-center text-sm text-gray-500 font-medium mt-6">
                        Already have an account? 
                        <button type="button" @click="isLogin = true" class="text-red-500 font-bold hover:underline focus:outline-none">Sign in here</button>
                    </p>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
