<style>
    .user-card-wrap { background: #16191f; border-radius: 14px; padding: 6px; }
    .user-card-avatar {
        background: linear-gradient(135deg, #6366f1, #ec4899);
    }
</style>

<div class="user-card-wrap">

    {{-- Profil user --}}
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-white/5 transition">
        <div class="user-card-avatar w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 text-white">
            {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
        </div>
        <div class="leading-tight">
            <p class="font-semibold text-sm text-white">{{ auth()->user()->name ?? 'messi' }}</p>
            <p class="text-xs text-slate-400">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Penyewa' }}</p>
        </div>
    </a>

    {{-- Tombol keluar akun --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-slate-300 hover:bg-white/5 transition">
            <span class="w-6 h-6 rounded-full bg-red-500/15 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-3.5 h-3.5">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="16 17 21 12 16 7" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="21" y1="12" x2="9" y2="12" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span>Keluar Akun</span>
        </button>
    </form>

</div>
