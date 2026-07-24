<button {{ $attributes->merge(['type' => 'submit', 'class' => 'button-effect inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-lg font-semibold text-xs text-zinc-950 uppercase tracking-widest hover:bg-amber-400 focus:bg-amber-400 active:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-[#141417] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
