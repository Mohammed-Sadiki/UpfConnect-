<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UniConnect') }} — {{ $title ?? 'Bienvenue' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
        @keyframes slideRight { from{transform:translateX(-100%)} to{transform:translateX(100%)} }
        .anim-up { animation: fadeInUp .6s ease both; }
        .anim-delay-1 { animation-delay:.15s; }
        .anim-delay-2 { animation-delay:.3s; }
        .float-anim { animation: float 4s ease-in-out infinite; }
        .btn-shine { position:relative; overflow:hidden; }
        .btn-shine::after {
            content:''; position:absolute; top:0; left:-100%; width:60%; height:100%;
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),transparent);
            animation: slideRight 2.5s infinite;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex">

<!-- LEFT PANEL — Branding -->
<div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden" style="background:linear-gradient(155deg,#0d1f3c 0%,#1a3a6b 50%,#2952a3 100%)">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-10" style="background:radial-gradient(circle,#c0001d,transparent);transform:translate(30%,-30%)"></div>
    <div class="absolute bottom-0 left-0 w-60 h-60 rounded-full opacity-10" style="background:radial-gradient(circle,#e8a020,transparent);transform:translate(-30%,30%)"></div>
    <div class="absolute inset-0 opacity-5" style="background:repeating-linear-gradient(45deg,transparent,transparent 30px,rgba(255,255,255,.3) 30px,rgba(255,255,255,.3) 31px)"></div>

    <!-- Logo -->
    <div class="relative z-10">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg" style="background:#c0001d">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <div>
                <span class="text-white font-bold text-2xl">UniConnect</span>
                <p class="text-xs font-light" style="color:#e8a020">Réseau Social Universitaire</p>
            </div>
        </div>
    </div>

    <!-- Center illustration & text -->
    <div class="relative z-10 text-center">
        <div class="float-anim mb-8">
            <div class="w-40 h-40 rounded-full mx-auto flex items-center justify-center shadow-2xl" style="background:rgba(255,255,255,0.1);border:2px solid rgba(255,255,255,0.2)">
                <svg class="w-20 h-20 text-white opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
        </div>
        <h2 class="text-3xl font-bold text-white leading-tight mb-4">Connectez-vous<br>avec votre communauté</h2>
        <p class="text-white/60 text-sm leading-relaxed max-w-sm mx-auto">La plateforme dédiée aux étudiants et enseignants pour partager, apprendre et évoluer ensemble.</p>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mt-10">
            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12)">
                <div class="text-2xl font-bold text-white">36+</div>
                <div class="text-xs text-white/60 mt-1">Membres</div>
            </div>
            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12)">
                <div class="text-2xl font-bold text-white">50+</div>
                <div class="text-xs text-white/60 mt-1">Publications</div>
            </div>
            <div class="rounded-xl p-4" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12)">
                <div class="text-2xl font-bold text-white">20+</div>
                <div class="text-xs text-white/60 mt-1">Événements</div>
            </div>
        </div>
    </div>

    <p class="relative z-10 text-white/30 text-xs text-center">&copy; {{ date('Y') }} UniConnect. Tous droits réservés.</p>
</div>

<!-- RIGHT PANEL — Form -->
<div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
    <div class="w-full max-w-md">
        <!-- Mobile logo -->
        <div class="lg:hidden flex items-center justify-center space-x-3 mb-8">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#1a3a6b">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <span class="text-xl font-bold" style="color:#1a3a6b">UniConnect</span>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8 anim-up" style="border-top:4px solid #1a3a6b">
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
