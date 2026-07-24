@props(['label' => 'Memuat data...'])

<div x-show="isLoading" x-cloak class="loader-overlay">
    <div class="loader-stage">
        <div class="loader-ground"></div>
        <div class="loader-figure">
            <svg viewBox="0 0 60 110">
                <g class="loader-torso-group" fill="#0b0f19">
                    <circle cx="30" cy="14" r="9" />
                    <path d="M20 24 Q30 20 40 24 L38 60 Q30 64 22 60 Z" />
                    <path d="M20 28 Q10 34 8 46 Q11 48 14 45 Q18 34 26 30 Z" />
                    <path d="M40 28 Q50 32 53 42 Q50 45 47 43 Q42 33 34 30 Z" />
                    <g class="loader-plant-leg">
                        <path d="M32 58 L38 100 Q34 104 29 102 L28 62 Z" />
                    </g>
                    <g class="loader-kick-leg">
                        <path d="M26 58 L20 96 Q25 101 30 98 L30 60 Z" />
                    </g>
                </g>
            </svg>
        </div>
        <div class="loader-ball"></div>
    </div>
    <p class="loader-label">{{ $label }}</p>
</div>
