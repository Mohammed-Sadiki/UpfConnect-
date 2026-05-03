<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UniConnect') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --upf-blue: #1a3a6b;
            --upf-red: #c0001d;
            --upf-gold: #e8a020;
            --upf-light: #f4f6fb;
            --upf-dark: #0d1f3c;
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background: var(--upf-light); }

        /* Animations */
        @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeInUp   { from { opacity:0; transform:translateY(20px); }  to { opacity:1; transform:translateY(0); } }
        @keyframes slideInLeft{ from { opacity:0; transform:translateX(-30px); } to { opacity:1; transform:translateX(0); } }
        @keyframes pulse-glow { 0%,100%{box-shadow:0 0 0 0 rgba(192,0,29,0.3);} 50%{box-shadow:0 0 0 8px rgba(192,0,29,0);} }
        @keyframes shimmer    { 0%{background-position:-200% 0;} 100%{background-position:200% 0;} }

        .anim-fade-down  { animation: fadeInDown .5s ease both; }
        .anim-fade-up    { animation: fadeInUp .5s ease both; }
        .anim-slide-left { animation: slideInLeft .5s ease both; }
        .anim-delay-1    { animation-delay:.1s; }
        .anim-delay-2    { animation-delay:.2s; }
        .anim-delay-3    { animation-delay:.3s; }
        .anim-delay-4    { animation-delay:.4s; }

        /* Navbar */
        .upf-navbar {
            background: linear-gradient(135deg, var(--upf-dark) 0%, var(--upf-blue) 100%);
            box-shadow: 0 4px 20px rgba(13,31,60,0.4);
        }
        .upf-navbar .nav-link {
            color: rgba(255,255,255,0.75);
            transition: all .25s;
            position: relative;
            padding-bottom: 2px;
        }
        .upf-navbar .nav-link::after {
            content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px;
            background: var(--upf-gold); transition: width .3s;
        }
        .upf-navbar .nav-link:hover { color:#fff; }
        .upf-navbar .nav-link:hover::after { width:100%; }

        /* Cards */
        .upf-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(26,58,107,0.08);
            transition: transform .3s, box-shadow .3s;
            border: 1px solid rgba(26,58,107,0.07);
        }
        .upf-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(26,58,107,0.15); }

        /* Sidebar profile banner */
        .profile-banner {
            background: linear-gradient(135deg, var(--upf-blue), var(--upf-dark));
        }

        /* Badge notification */
        .notif-badge {
            background: var(--upf-red);
            animation: pulse-glow 2s infinite;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--upf-blue), #2952a3);
            color: #fff; border-radius: 8px; padding: .55rem 1.25rem;
            font-weight: 600; font-size:.875rem;
            transition: all .25s; box-shadow: 0 4px 12px rgba(26,58,107,0.3);
        }
        .btn-primary:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(26,58,107,0.4); }
        .btn-red {
            background: linear-gradient(135deg, var(--upf-red), #a0001a);
            color:#fff; border-radius:8px; padding:.55rem 1.25rem;
            font-weight:600; font-size:.875rem;
            transition:all .25s; box-shadow:0 4px 12px rgba(192,0,29,0.3);
        }
        .btn-red:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(192,0,29,0.4); }

        /* Scrollbar */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background:#f1f1f1; }
        ::-webkit-scrollbar-thumb { background:var(--upf-blue); border-radius:3px; }

        /* Flash toast */
        .flash-toast { animation: fadeInUp .4s ease; }

        /* Active nav link */
        .nav-active { color:#fff !important; }
        .nav-active::after { width:100% !important; }
    </style>
</head>
<body class="antialiased">

<!-- TOP STRIP -->
<div class="upf-navbar sticky top-0 z-50 anim-fade-down">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 flex-shrink-0">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background:var(--upf-red)">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <div>
                <span class="text-white font-bold text-lg leading-none">UniConnect</span>
                <span class="block text-xs leading-none" style="color:var(--upf-gold)">Réseau Universitaire</span>
            </div>
        </a>

        <!-- Search -->
        <div class="hidden md:flex flex-1 max-w-sm mx-6">
            <div class="relative w-full">
                <input type="text" placeholder="Rechercher des personnes, posts..."
                    class="w-full bg-white/10 text-white placeholder-white/50 rounded-full px-4 py-2 pl-10 text-sm focus:outline-none focus:bg-white/20 transition border border-white/10">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <!-- Nav Links -->
        <div class="flex items-center space-x-1">
            <a href="{{ route('dashboard') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] font-medium">Accueil</span>
            </a>
            <a href="{{ route('connections.index') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('connections.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                <span class="text-[10px] font-medium">Réseau</span>
            </a>
            <a href="{{ route('events.index') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('events.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] font-medium">Événements</span>
            </a>
            <a href="{{ route('messages.index') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('messages.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span class="text-[10px] font-medium">Messages</span>
            </a>
            <a href="{{ route('notifications.index') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition relative {{ request()->routeIs('notifications.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="text-[10px] font-medium">Notifs</span>
                <span class="notif-badge absolute top-0 right-1 w-4 h-4 rounded-full text-white text-[9px] font-bold flex items-center justify-center">3</span>
            </a>

            <!-- Profile dropdown -->
            <div x-data="{ open: false }" class="relative ml-1">
                <button @click="open=!open" class="flex items-center space-x-2 bg-white/10 hover:bg-white/20 rounded-full pl-1 pr-3 py-1 transition border border-white/10">
                    <img class="w-7 h-7 rounded-full object-cover ring-2 ring-white/30"
                         src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1a3a6b&color=fff' }}"
                         alt="">
                    <span class="text-white text-xs font-medium hidden sm:block max-w-[80px] truncate">{{ auth()->user()->name }}</span>
                    <svg class="w-3 h-3 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition @click.away="open=false"
                     class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl py-1 z-50 border border-gray-100">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Mon profil
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier le profil
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-sm text-purple-700 hover:bg-purple-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Panel Admin
                    </a>
                    @endif
                    <div class="border-t border-gray-100 mt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FLASH MESSAGES -->
@if(session('success'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     class="flash-toast fixed bottom-6 right-6 z-50 flex items-center space-x-3 bg-white border-l-4 px-5 py-4 rounded-xl shadow-2xl" style="border-color:var(--upf-blue)">
    <svg class="w-5 h-5 flex-shrink-0" style="color:var(--upf-blue)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm font-medium text-gray-800">{{ session('success') }}</p>
</div>
@endif
@if(session('error'))
<div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4000)"
     class="flash-toast fixed bottom-6 right-6 z-50 flex items-center space-x-3 bg-white border-l-4 border-red-500 px-5 py-4 rounded-xl shadow-2xl">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm font-medium text-gray-800">{{ session('error') }}</p>
</div>
@endif

<!-- MAIN CONTENT -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- LEFT SIDEBAR -->
        <div class="hidden lg:block lg:col-span-1 anim-slide-left">
            <div class="upf-card overflow-hidden sticky top-24">
                <div class="profile-banner h-20 relative">
                    <div class="absolute inset-0 opacity-20" style="background:repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,.1) 10px,rgba(255,255,255,.1) 20px)"></div>
                </div>
                <div class="px-4 pb-4 relative">
                    <img class="w-16 h-16 rounded-full border-4 border-white object-cover absolute -top-8 left-1/2 -translate-x-1/2 shadow-lg"
                         src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1a3a6b&color=fff' }}"
                         alt="">
                    <div class="text-center mt-10">
                        <h2 class="font-bold text-gray-900 text-sm">{{ auth()->user()->name }}</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->bio ?? auth()->user()->department }}</p>
                        <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full"
                              style="background:rgba(26,58,107,0.1); color:var(--upf-blue)">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
                <div class="border-t border-gray-100 px-4 py-3 space-y-2">
                    <a href="{{ route('profile.show', auth()->id()) }}" class="flex items-center space-x-2 text-sm text-gray-600 hover:text-blue-700 transition p-2 rounded-lg hover:bg-blue-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Voir mon profil</span>
                    </a>
                    <a href="{{ route('connections.index') }}" class="flex items-center space-x-2 text-sm text-gray-600 hover:text-blue-700 transition p-2 rounded-lg hover:bg-blue-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        <span>Mes relations</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="flex items-center space-x-2 text-sm text-gray-600 hover:text-blue-700 transition p-2 rounded-lg hover:bg-blue-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Événements</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- CENTER CONTENT -->
        <div class="lg:col-span-2 anim-fade-up">
            {{ $slot }}
        </div>

        <!-- RIGHT SIDEBAR -->
        <div class="hidden lg:block lg:col-span-1 anim-fade-up anim-delay-2">
            <!-- University banner -->
            <div class="upf-card overflow-hidden mb-4">
                <div class="h-2" style="background:linear-gradient(90deg,var(--upf-blue),var(--upf-red))"></div>
                <div class="p-4">
                    <h3 class="font-bold text-sm text-gray-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" style="color:var(--upf-red)" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                        À la une
                    </h3>
                    <ul class="space-y-3">
                        <li class="border-b border-gray-50 pb-3">
                            <a href="#" class="text-sm font-medium text-gray-800 hover:text-blue-700 transition leading-tight block">Journée portes ouvertes 2026</a>
                            <p class="text-xs text-gray-400 mt-1">12 450 vues</p>
                        </li>
                        <li class="border-b border-gray-50 pb-3">
                            <a href="#" class="text-sm font-medium text-gray-800 hover:text-blue-700 transition leading-tight block">Nouvelles bourses d'excellence</a>
                            <p class="text-xs text-gray-400 mt-1">8 300 vues</p>
                        </li>
                        <li>
                            <a href="#" class="text-sm font-medium text-gray-800 hover:text-blue-700 transition leading-tight block">Concours nationaux — Résultats</a>
                            <p class="text-xs text-gray-400 mt-1">5 120 vues</p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick stats -->
            <div class="upf-card p-4">
                <h3 class="font-bold text-sm text-gray-800 mb-3">Votre activité</h3>
                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-lg p-3 text-center" style="background:rgba(26,58,107,0.06)">
                        <div class="font-bold text-lg" style="color:var(--upf-blue)">0</div>
                        <div class="text-xs text-gray-500">Relations</div>
                    </div>
                    <div class="rounded-lg p-3 text-center" style="background:rgba(192,0,29,0.06)">
                        <div class="font-bold text-lg" style="color:var(--upf-red)">0</div>
                        <div class="text-xs text-gray-500">Posts</div>
                    </div>
                </div>
            </div>

            <footer class="mt-4 text-center text-xs text-gray-400 space-x-2">
                <span>&copy; 2026 UniConnect</span>
                <span>•</span>
                <a href="#" class="hover:text-gray-600">Politique</a>
                <span>•</span>
                <a href="#" class="hover:text-gray-600">Aide</a>
            </footer>
        </div>
    </div>
</main>
</body>
</html>
