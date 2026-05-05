<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UPFConnect') }} — {{ $title ?? 'Bienvenue' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --upf-neon-blue: #0ea5e9;
            --upf-neon-purple: #8b5cf6;
            --upf-light-base: #f0f4f8;
            --upf-light-surface: rgba(255, 255, 255, 0.7);
            --upf-glass-border: rgba(255, 255, 255, 0.5);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        * { font-family: 'Rajdhani', sans-serif; }
        h1, h2, h3, h4, h5, h6, .brand-text { font-family: 'Orbitron', sans-serif; }
        body { 
            background-color: var(--upf-light-base); 
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(139, 92, 246, 0.08), transparent 30%),
                radial-gradient(circle at 85% 30%, rgba(14, 165, 233, 0.08), transparent 30%),
                linear-gradient(to bottom, #ffffff, var(--upf-light-base));
            color: var(--text-main);
        }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(30px);filter:blur(5px);} to{opacity:1;transform:translateY(0);filter:blur(0);} }
        @keyframes float { 0%,100%{transform:translateY(0);box-shadow:0 8px 32px rgba(14,165,233,0.15);} 50%{transform:translateY(-12px);box-shadow:0 12px 40px rgba(139,92,246,0.25);} }
        @keyframes slideRight { from{transform:translateX(-100%)} to{transform:translateX(100%)} }
        .anim-up { animation: fadeInUp .6s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-delay-1 { animation-delay:.15s; }
        .anim-delay-2 { animation-delay:.3s; }
        .float-anim { animation: float 4s ease-in-out infinite; }
        
        .glass-panel {
            background: var(--upf-light-surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--upf-glass-border);
            box-shadow: 0 8px 32px rgba(14, 165, 233, 0.05), inset 0 0 15px rgba(255, 255, 255, 0.5);
        }
        
        /* Overrides for inputs and labels in guest slot */
        .text-gray-900, .text-gray-800, .text-gray-700 { color: #0f172a !important; }
        .text-gray-600, .text-gray-500 { color: #64748b !important; }
        .bg-white { background: transparent !important; }
        input.bg-white, select.bg-white, textarea.bg-white, input, select, textarea { 
            background: rgba(255,255,255,0.8) !important; 
            border: 1px solid rgba(14,165,233,0.2) !important; 
            color: #0f172a !important;
            border-radius: 8px !important;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--upf-neon-blue) !important; box-shadow: 0 0 10px rgba(14,165,233,0.2) !important; outline: none !important; ring-color: var(--upf-neon-blue) !important; ring-width: 1px !important;}
        .bg-gray-50 { background-color: transparent !important; }
        .border-gray-300 { border-color: rgba(14,165,233,0.2) !important; }
        
        /* Buttons inside form overrides */
        .bg-blue-600, .bg-indigo-600 {
            background: linear-gradient(135deg, rgba(14,165,233,0.9), rgba(139,92,246,0.9)) !important;
            border: 1px solid var(--upf-neon-blue) !important;
            color: #fff !important;
            font-family: 'Orbitron', sans-serif !important;
            text-transform: uppercase !important;
            box-shadow: 0 4px 15px rgba(14,165,233,0.3) !important;
            transition: all 0.3s !important;
        }
        .hover\:bg-blue-700:hover, .hover\:bg-indigo-700:hover {
            box-shadow: 0 6px 20px rgba(14,165,233,0.5) !important; 
            transform: translateY(-2px) !important; 
            background: linear-gradient(135deg, rgba(14,165,233,1), rgba(139,92,246,1)) !important;
            color: #fff !important;
        }
        .text-blue-600, .text-indigo-600 { color: var(--upf-neon-blue) !important; }
        .hover\:text-blue-500:hover, .hover\:text-indigo-500:hover { color: var(--upf-neon-purple) !important; text-shadow: 0 0 5px rgba(139,92,246,0.3) !important;}
    </style>
</head>
<body class="antialiased min-h-screen flex">

<!-- LEFT PANEL — Branding -->
<div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden" style="background:linear-gradient(155deg, rgba(240,244,248,0.9) 0%, rgba(255,255,255,0.8) 50%, rgba(240,244,248,0.9) 100%)">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-20" style="background:radial-gradient(circle, var(--upf-neon-purple), transparent);transform:translate(30%,-30%); filter:blur(40px);"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full opacity-20" style="background:radial-gradient(circle, var(--upf-neon-blue), transparent);transform:translate(-30%,30%); filter:blur(40px);"></div>
    <div class="absolute inset-0 opacity-10" style="background:repeating-linear-gradient(45deg,transparent,transparent 30px,rgba(14,165,233,.05) 30px,rgba(14,165,233,.05) 31px)"></div>

    <!-- Logo -->
    <div class="relative z-10">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-[0_4px_15px_rgba(14,165,233,0.2)] border border-[var(--upf-neon-blue)]" style="background:rgba(255,255,255,0.8)">
                <svg class="w-7 h-7 text-[var(--upf-neon-blue)]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <div>
                <span class="text-slate-900 font-bold text-2xl brand-text drop-shadow-[0_2px_4px_rgba(14,165,233,0.2)]">UPFConnect</span>
                <p class="text-xs font-semibold tracking-wider" style="color:var(--upf-neon-blue)">Réseau Social Universitaire</p>
            </div>
        </div>
    </div>

    <!-- Center illustration & text -->
    <div class="relative z-10 text-center">
        <div class="float-anim mb-8">
            <div class="w-40 h-40 rounded-full mx-auto flex items-center justify-center shadow-[0_8px_30px_rgba(139,92,246,0.2)] border border-[var(--upf-neon-purple)]" style="background:rgba(255,255,255,0.8);">
                <svg class="w-20 h-20 opacity-90 drop-shadow-[0_4px_10px_rgba(139,92,246,0.3)]" style="color:var(--upf-neon-purple)" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-slate-900 leading-tight mb-4 brand-text tracking-wide drop-shadow-[0_2px_4px_rgba(14,165,233,0.1)]">Connectez-vous<br>avec votre communauté</h2>
        <p class="text-slate-600 text-sm leading-relaxed max-w-sm mx-auto font-medium">La plateforme dédiée aux étudiants et enseignants pour partager, apprendre et évoluer ensemble.</p>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mt-10">
            <div class="glass-panel rounded-xl p-4 border border-blue-100">
                <div class="text-2xl font-bold text-slate-900 brand-text drop-shadow-[0_2px_4px_rgba(14,165,233,0.2)]">36+</div>
                <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Membres</div>
            </div>
            <div class="glass-panel rounded-xl p-4 border border-purple-100">
                <div class="text-2xl font-bold text-slate-900 brand-text drop-shadow-[0_2px_4px_rgba(139,92,246,0.2)]">50+</div>
                <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Publications</div>
            </div>
            <div class="glass-panel rounded-xl p-4 border border-blue-100">
                <div class="text-2xl font-bold text-slate-900 brand-text drop-shadow-[0_2px_4px_rgba(14,165,233,0.2)]">20+</div>
                <div class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-semibold">Événements</div>
            </div>
        </div>
    </div>

    <p class="relative z-10 text-slate-400 text-xs text-center font-medium">&copy; {{ date('Y') }} UPFConnect. Tous droits réservés.</p>
</div>

<!-- RIGHT PANEL — Form -->
<div class="w-full lg:w-1/2 flex items-center justify-center p-8 relative">
    <!-- Glow effect behind form -->
    <div class="absolute w-[300px] h-[300px] bg-[var(--upf-neon-blue)] opacity-5 rounded-full blur-[80px]"></div>
    
    <div class="w-full max-w-md relative z-10">
        <!-- Mobile logo -->
        <div class="lg:hidden flex items-center justify-center space-x-3 mb-8">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-[0_4px_10px_rgba(14,165,233,0.2)] border border-[var(--upf-neon-blue)]" style="background:rgba(255,255,255,0.8)">
                <svg class="w-6 h-6 text-[var(--upf-neon-blue)]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <span class="text-xl font-bold brand-text text-slate-900 drop-shadow-[0_2px_4px_rgba(14,165,233,0.2)]">UPFConnect</span>
        </div>

        <div class="glass-panel rounded-2xl p-8 anim-up border-t border-[var(--upf-neon-blue)]" style="border-top-width: 3px;">
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
