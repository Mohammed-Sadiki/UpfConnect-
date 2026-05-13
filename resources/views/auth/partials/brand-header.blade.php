@props(['showTagline' => true])
<div {{ $attributes->merge(['class' => 'text-center']) }}>
    <div class="mx-auto mb-4 flex h-[3.75rem] w-[3.5rem] items-center justify-center">
        <svg viewBox="0 0 64 72" class="h-[3.75rem] w-14 drop-shadow-md" aria-hidden="true">
            <defs>
                <linearGradient id="authHexGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#4A6CF7"/>
                    <stop offset="100%" stop-color="#7E3AF2"/>
                </linearGradient>
            </defs>
            <path fill="url(#authHexGrad)" d="M32 4 L58 19 L58 53 L32 68 L6 53 L6 19 Z"/>
            <path fill="white" fill-opacity="0.95" d="M32 20L18 27v2l14 7 14-7v-2L32 20zm-11 9.5l11 5.5 11-5.5V44l-11 5.5L21 44V29.5z"/>
        </svg>
    </div>
    <h1 class="text-xl sm:text-[1.4rem] font-bold tracking-tight text-slate-900">
        <span class="text-slate-900">UPF</span><span class="bg-gradient-to-r from-[#4A6CF7] to-[#7E3AF2] bg-clip-text text-transparent">Connect</span>
    </h1>
    @if($showTagline)
        <p class="mt-1.5 text-[10px] font-medium uppercase tracking-[0.2em] text-slate-500">Réseau social universitaire</p>
    @endif
</div>
