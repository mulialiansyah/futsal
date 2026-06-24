<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Daftar Sebagai (Role) -->
        <div class="mt-6">
            <x-input-label :value="__('Daftar Sebagai')" />

            <div class="grid grid-cols-2 gap-3 mt-2">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="customer"
                           class="hidden peer"
                           {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center text-center gap-2 p-4 rounded-lg border-2 border-gray-200
                                peer-checked:border-gray-800 peer-checked:bg-gray-50 hover:border-gray-400 transition">
                        <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.5a3 3 0 110 6 3 3 0 010-6zM4 19.5a8 8 0 0116 0" />
                        </svg>
                        <span class="font-semibold text-sm text-gray-800">Penyewa</span>
                        <span class="text-xs text-gray-500">Cari &amp; sewa lapangan futsal</span>
                    </div>
                </label>

                <label class="cursor-pointer">
                    <input type="radio" name="role" value="admin"
                           class="hidden peer"
                           {{ old('role') === 'admin' ? 'checked' : '' }}>
                    <div class="flex flex-col items-center text-center gap-2 p-4 rounded-lg border-2 border-gray-200
                                peer-checked:border-gray-800 peer-checked:bg-gray-50 hover:border-gray-400 transition">
                        <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6" />
                        </svg>
                        <span class="font-semibold text-sm text-gray-800">Pengelola</span>
                        <span class="text-xs text-gray-500">Daftarkan &amp; kelola lapangan</span>
                    </div>
                </label>
            </div>

            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>