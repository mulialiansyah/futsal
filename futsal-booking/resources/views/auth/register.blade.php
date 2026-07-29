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
        <div class="mt-4" x-data="{ regEmail: '{{ old('email') }}', typoSuggestion: null }">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" x-model="regEmail"
                          @input.debounce.300ms="typoSuggestion = window.checkEmailTypo ? window.checkEmailTypo(regEmail) : null"
                          @blur="typoSuggestion = window.checkEmailTypo ? window.checkEmailTypo(regEmail) : null"
                          required autocomplete="username" />
            <template x-if="typoSuggestion">
                <div class="mt-2 p-2.5 bg-amber-50 border border-amber-200 text-amber-900 rounded-lg text-xs flex items-center justify-between gap-2 shadow-sm">
                    <span>💡 Apakah maksud Anda <strong class="font-bold underline" x-text="typoSuggestion.suggested"></strong>?</span>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" @click="regEmail = typoSuggestion.suggested; typoSuggestion = null" class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded text-[11px] transition">Ganti</button>
                        <button type="button" @click="typoSuggestion = null" class="px-2 py-1 text-amber-700 hover:bg-amber-100 font-semibold rounded text-[11px] transition">Abaikan</button>
                    </div>
                </div>
            </template>
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
