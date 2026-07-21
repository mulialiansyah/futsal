@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-zinc-700 bg-[#18181C] text-zinc-200 focus:border-amber-500 focus:ring-amber-500 rounded-lg shadow-sm placeholder-zinc-500']) }}>
