<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FutsalKite — Lupa Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="flex flex-col items-center mb-6">
            <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-5 h-5" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke="white" stroke-width="1.5"/>
                    <path d="M12 6.5l3.2 2.3-1.2 3.8h-4l-1.2-3.8L12 6.5z" fill="white"/>
                    <path d="M12 3v3.5M12 21v-3.2M3 12h3.5M21 12h-3.3M6.4 6.4l2 2M17.6 6.4l-2 2M6.4 17.6l2.2-2M17.6 17.6l-2.2-2"
                        stroke="white" stroke-width="1.1" stroke-linecap="round"/>
                </svg>
            </div>
            <h1 class="text-lg font-semibold text-gray-900">FutsalKite</h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-gray-900 mb-1">Lupa password?</h2>
            <p class="text-sm text-gray-500 mb-6">Masukkan email akun Anda, kami akan mengirimkan tautan untuk membuat password baru.</p>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-md px-3 py-2">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div x-data="{ forgotEmail: '{{ old('email') }}', typoSuggestion: null }">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" x-model="forgotEmail"
                           @input.debounce.300ms="typoSuggestion = window.checkEmailTypo ? window.checkEmailTypo(forgotEmail) : null"
                           @blur="typoSuggestion = window.checkEmailTypo ? window.checkEmailTypo(forgotEmail) : null"
                           required autofocus placeholder="nama@email.com"
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                    <template x-if="typoSuggestion">
                        <div class="mt-2 p-2.5 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg text-xs flex items-center justify-between gap-2 shadow-sm">
                            <span>💡 Apakah maksud Anda <strong class="font-bold underline" x-text="typoSuggestion.suggested"></strong>?</span>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" @click="forgotEmail = typoSuggestion.suggested; typoSuggestion = null" class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded text-[11px] transition">Ganti</button>
                                <button type="button" @click="typoSuggestion = null" class="px-2 py-1 text-amber-700 hover:bg-amber-100 font-semibold rounded text-[11px] transition">Abaikan</button>
                            </div>
                        </div>
                    </template>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full rounded-md bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium py-2.5 transition">
                    Kirim Tautan Reset Password
                </button>
            </form>

            <div class="text-center mt-5">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Kembali ke halaman masuk</a>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ now()->year }} FutsalKite</p>
    </div>
</body>
</html>
