@props(['label' => 'Sedang memuat...'])

<div x-show="isLoading" x-cloak class="kick-loader-overlay" role="status" aria-live="polite">
    <svg class="kick-loader-illustration" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <circle class="kick-loader-ball" cx="70" cy="78" r="9" fill="#F5A623" />
        <circle cx="42" cy="22" r="8" fill="#E8AD7D" />
        <path d="M35 19 Q42 12 49 19 Q49 15 42 14 Q35 15 35 19 Z" fill="#2B1B12" />
        <path d="M42 30 L40 52 L48 50 L50 30 Z" fill="#F5A623" />
        <path d="M40 49 L38 56 L50 56 L48 49 Z" fill="#1E1E1E" />
        <path d="M42 33 L28 40 L30 44 L44 39 Z" fill="#E8AD7D" />
        <path class="kick-loader-arm" d="M46 33 L58 26 L60 30 L48 38 Z" fill="#E8AD7D" />
        <path d="M43 50 L38 66 L44 67 L48 51 Z" fill="#1E1E1E" />
        <path d="M38 66 L36 78 L42 79 L44 67 Z" fill="#E8AD7D" />
        <path d="M35 78 L43 79 L43 82 L34 82 Z" fill="#161616" />
        <g class="kick-loader-leg">
            <path d="M47 50 L55 60 L61 68 L56 72 L49 62 L44 51 Z" fill="#1E1E1E" />
            <path d="M55 60 L61 68 L64 71 L58 74 L52 65 Z" fill="#E8AD7D" />
            <path d="M60 68 L66 73 L64 76 L57 73 Z" fill="#161616" />
        </g>
    </svg>
    <div class="kick-loader-copy">
        <div class="kick-loader-brand">FutsalKite</div>
        <div class="kick-loader-label">{{ $label }}</div>
    </div>
</div>

<style>
    .kick-loader-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
        background: #0A0A0A;
    }

    .kick-loader-illustration { width: 96px; height: 96px; overflow: visible; }
    .kick-loader-ball { transform-box: view-box; transform-origin: center; animation: kick-loader-ball 420ms cubic-bezier(.34,1.56,.64,1) infinite alternate; }
    .kick-loader-arm { transform-box: view-box; transform-origin: 46px 33px; animation: kick-loader-arm 420ms cubic-bezier(.34,1.56,.64,1) infinite alternate; }
    .kick-loader-leg { transform-box: view-box; transform-origin: 47px 50px; animation: kick-loader-leg 420ms cubic-bezier(.34,1.56,.64,1) infinite alternate; }
    .kick-loader-copy { display: flex; flex-direction: column; align-items: center; gap: 0.375rem; }
    .kick-loader-brand { color: #fff; font-size: 1rem; font-weight: 700; }
    .kick-loader-label { color: #8a8a8a; font-size: 0.8125rem; }

    @keyframes kick-loader-ball { to { transform: translate(8px, -6px); } }
    @keyframes kick-loader-arm { to { transform: rotate(-18deg); } }
    @keyframes kick-loader-leg { to { transform: rotate(28deg); } }

    @media (prefers-reduced-motion: reduce) {
        .kick-loader-ball, .kick-loader-arm, .kick-loader-leg { animation: none; }
    }
</style>
