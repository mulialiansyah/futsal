<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-zinc-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6"
          @submit.prevent="
            if (typoSuggestion) {
                submitError = 'Email sepertinya salah ketik. Apakah maksud Anda ' + typoSuggestion.suggested + '?';
                $el.querySelector('#email').focus();
                return false;
            }
          ">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div x-data="{ profileEmail: '{{ old('email', $user->email) }}', typoSuggestion: null, submitError: null }">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" x-model="profileEmail"
                          @input.debounce.300ms="typoSuggestion = window.checkEmailTypo ? window.checkEmailTypo(profileEmail) : null; submitError = null"
                          @blur="typoSuggestion = window.checkEmailTypo ? window.checkEmailTypo(profileEmail) : null"
                          required autocomplete="username" />
            <template x-if="typoSuggestion">
                <div class="mt-2 p-2.5 bg-amber-500/10 border border-amber-500/30 text-amber-300 rounded-lg text-xs flex items-center justify-between gap-2 shadow-sm">
                    <span>💡 Apakah maksud Anda <strong class="font-bold underline" x-text="typoSuggestion.suggested"></strong>?</span>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" @click="profileEmail = typoSuggestion.suggested; typoSuggestion = null; submitError = null" class="px-2 py-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded text-[11px] transition">Ganti</button>
                        <button type="button" @click="typoSuggestion = null" class="px-2 py-1 text-amber-400 hover:bg-amber-500/20 font-semibold rounded text-[11px] transition">Abaikan</button>
                    </div>
                </div>
            </template>
            <template x-if="submitError">
                <div class="mt-2 text-sm text-red-400" x-text="submitError"></div>
            </template>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-zinc-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-zinc-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-zinc-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
