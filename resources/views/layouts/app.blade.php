<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UPFConnect') }}</title>
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
        body { 
            background-color: var(--upf-light-base); 
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(139, 92, 246, 0.08), transparent 30%),
                radial-gradient(circle at 85% 30%, rgba(14, 165, 233, 0.08), transparent 30%),
                linear-gradient(to bottom, #ffffff, var(--upf-light-base));
            background-attachment: fixed;
            color: var(--text-main); 
        }
        h1, h2, h3, h4, h5, h6, .brand-text {
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 0.5px;
            color: #0f172a !important;
        }

        /*
         * UI principalement claire : en prefers-color-scheme: dark, Tailwind active
         * dark:text-white / dark:bg-neutral-* et le texte disparaît sur glass-card blanc.
         * On force texte et champs lisibles dans le contenu principal.
         */
        @media (prefers-color-scheme: dark) {
            main .glass-card .dark\:text-white,
            main .upf-card .dark\:text-white,
            main .glass-card h1,
            main .glass-card h2,
            main .glass-card h3,
            main .glass-card h4,
            main .upf-card h1,
            main .upf-card h2,
            main .upf-card h3 {
                color: #0f172a !important;
            }
            main .glass-card a[class*="text-gray-900"],
            main .glass-card .text-gray-900 {
                color: #0f172a !important;
            }
            main input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]):not([type="file"]),
            main textarea,
            main select {
                background-color: #ffffff !important;
                color: #0f172a !important;
                border-color: #cbd5e1 !important;
            }
            main input::placeholder,
            main textarea::placeholder {
                color: #64748b !important;
                opacity: 1;
            }
            main .glass-card .text-gray-500,
            main .glass-card .text-gray-600 {
                color: #475569 !important;
            }
        }
        
        /* Glassmorphism Cards */
        .glass-card, .upf-card { 
            background: var(--upf-light-surface); 
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--upf-glass-border);
            box-shadow: 0 8px 32px rgba(14, 165, 233, 0.05), inset 0 0 15px rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        .upf-card::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.8), transparent);
            transform: skewX(-25deg); transition: all 0.7s;
        }
        .upf-card:hover::before { left: 200%; }
        .upf-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(14, 165, 233, 0.15), inset 0 0 20px rgba(255, 255, 255, 0.8);
            border-color: rgba(14, 165, 233, 0.3);
        }

        /* Navbar */
        .upf-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--upf-glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }
        .upf-navbar .nav-link {
            color: var(--text-muted);
            transition: all .3s ease;
            position: relative;
            font-weight: 600;
        }
        .upf-navbar .nav-link::after {
            content:''; position:absolute; bottom:-4px; left:50%; width:0; height:2px;
            background: var(--upf-neon-blue); transition: all .3s ease; transform: translateX(-50%);
            box-shadow: 0 0 8px var(--upf-neon-blue);
        }
        .upf-navbar .nav-link:hover { color: var(--upf-neon-blue); text-shadow: 0 0 5px rgba(14,165,233,0.3); }
        .upf-navbar .nav-link:hover::after { width:80%; }
        .nav-active { color: var(--upf-neon-blue) !important; text-shadow: 0 0 5px rgba(14,165,233,0.3); }
        .nav-active::after { width:80% !important; box-shadow: 0 0 8px var(--upf-neon-blue); }

        /* Sidebar profile banner */
        .profile-banner {
            background: linear-gradient(135deg, rgba(14,165,233,0.15), rgba(139,92,246,0.15));
            border-bottom: 1px solid rgba(14,165,233,0.2);
            position: relative;
        }
        .profile-banner::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px;
            background: var(--upf-neon-blue); box-shadow: 0 0 10px var(--upf-neon-blue);
        }

        /* Badge notification */
        .notif-badge {
            background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
            animation: pulse-glow 2s infinite;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, rgba(14,165,233,0.9), rgba(139,92,246,0.9));
            border: 1px solid var(--upf-neon-blue);
            color: #fff; border-radius: 8px; padding: .55rem 1.25rem;
            font-weight: 600; font-size:1rem; font-family: 'Orbitron', sans-serif;
            text-transform: uppercase; letter-spacing: 1px;
            transition: all .3s; box-shadow: 0 4px 15px rgba(14,165,233,0.3);
            position: relative; overflow: hidden;
        }
        .btn-primary:hover { 
            transform:translateY(-2px); 
            box-shadow:0 6px 20px rgba(14,165,233,0.5); 
            background: linear-gradient(135deg, rgba(14,165,233,1), rgba(139,92,246,1));
            color: #fff;
        }
        .btn-red {
            background: linear-gradient(135deg, rgba(239,68,68,0.9), rgba(220,38,38,0.9));
            border: 1px solid #ef4444;
            color:#fff; border-radius:8px; padding:.55rem 1.25rem;
            font-weight:600; font-size:.875rem; font-family: 'Orbitron', sans-serif;
            transition:all .3s; box-shadow:0 4px 15px rgba(239,68,68,0.3);
        }
        .btn-red:hover { 
            transform:translateY(-2px); 
            box-shadow:0 6px 20px rgba(239,68,68,0.5); 
            background: linear-gradient(135deg, #ef4444, #dc2626); 
            color: #fff;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width:6px; }
        ::-webkit-scrollbar-track { background: #e2e8f0; }
        ::-webkit-scrollbar-thumb { background: var(--upf-neon-blue); border-radius:3px; }

        /* Animations */
        @keyframes fadeInDown { from { opacity:0; transform:translateY(-20px); filter: blur(5px); } to { opacity:1; transform:translateY(0); filter: blur(0); } }
        @keyframes fadeInUp   { from { opacity:0; transform:translateY(20px); filter: blur(5px); }  to { opacity:1; transform:translateY(0); filter: blur(0); } }
        @keyframes slideInLeft{ from { opacity:0; transform:translateX(-30px); filter: blur(5px); } to { opacity:1; transform:translateX(0); filter: blur(0); } }
        @keyframes pulse-glow { 0%,100%{box-shadow:0 0 5px rgba(239,68,68,0.5);} 50%{box-shadow:0 0 15px rgba(239,68,68,0.8);} }

        .anim-fade-down  { animation: fadeInDown .5s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .anim-fade-up    { animation: fadeInUp .5s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .anim-slide-left { animation: slideInLeft .5s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .anim-delay-1    { animation-delay:.1s; }
        .anim-delay-2    { animation-delay:.2s; }
        .anim-delay-3    { animation-delay:.3s; }
        .anim-delay-4    { animation-delay:.4s; }

        /* Flash toast */
        .flash-toast { animation: fadeInUp .4s ease; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border: 1px solid var(--upf-glass-border); color: var(--text-main); }

        /* Global Theme Overrides — uniquement en thème clair pour ne pas écraser dark:text-* (contraste illisible sinon) */
        @media (prefers-color-scheme: light) {
            .text-gray-800, .text-gray-900 { color: #0f172a !important; text-shadow: none; }
            .text-gray-600, .text-gray-500, .text-gray-400 { color: #475569 !important; }
        }
        .bg-white { background-color: var(--upf-light-surface) !important; }
        .border-gray-100, .border-gray-50 { border-color: rgba(0,0,0,0.05) !important; }
        .hover\:bg-blue-50:hover { background-color: rgba(14,165,233,0.1) !important; color: var(--upf-neon-blue) !important; }
        .hover\:bg-purple-50:hover { background-color: rgba(139,92,246,0.1) !important; color: var(--upf-neon-purple) !important; }
        .hover\:bg-red-50:hover { background-color: rgba(239,68,68,0.1) !important; color: #ef4444 !important; }
        
        .upf-navbar .text-white { color: #0f172a !important; }
        .upf-navbar .text-white\/50 { color: #64748b !important; }
        .upf-navbar .bg-white\/10 { background-color: rgba(0,0,0,0.03) !important; border-color: rgba(0,0,0,0.1) !important; color: #0f172a !important; }
        .upf-navbar .hover\:bg-white\/10:hover { background-color: rgba(14,165,233,0.1) !important; color: var(--upf-neon-blue) !important; }
        .upf-navbar .hover\:bg-white\/20:hover { background-color: rgba(14,165,233,0.1) !important; }
        .upf-navbar input.text-white { color: #0f172a !important; }
        .upf-navbar input::placeholder { color: #94a3b8 !important; }
        
        /* Make svg in navbar dark */
        .upf-navbar svg { color: #0f172a; }
        .nav-link svg { color: inherit; }
        .upf-navbar .text-white\/70 { color: #475569 !important; }
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
                <span class="text-white font-bold text-lg leading-none">UPFConnect</span>
                <span class="block text-xs leading-none" style="color:var(--upf-gold)">Réseau Universitaire</span>
            </div>
        </a>

        <!-- Search -->
        <form action="{{ route('search') }}" method="GET" class="hidden md:flex flex-1 max-w-sm mx-6">
            <div class="relative w-full">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher des personnes, posts..."
                    class="w-full bg-white/10 text-white placeholder-white/50 rounded-full px-4 py-2 pl-10 text-sm focus:outline-none focus:bg-white/20 transition border border-white/10">
                <svg class="w-4 h-4 absolute left-3 top-2.5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </form>

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
            <a href="{{ route('groups.index') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('groups.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-[10px] font-medium">Groupes</span>
            </a>
            <a href="{{ route('messages.index') }}" class="nav-link flex flex-col items-center px-3 py-1 rounded-lg hover:bg-white/10 transition {{ request()->routeIs('messages.*') ? 'nav-active' : '' }}">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span class="text-[10px] font-medium">Messages</span>
            </a>
            {{-- Notification dropdown --}}
            <div x-data="{ open: false, tab: 'all' }" class="relative">
                <button @click="open=!open" @click.stop
                        class="nav-link flex flex-col items-center px-3 py-1 rounded-lg transition relative"
                        :class="open ? 'nav-active bg-cyan-50' : 'hover:bg-white/10 {{ request()->routeIs('notifications.*') ? 'nav-active' : '' }}'">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         :style="open ? 'color:var(--upf-neon-blue)' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="text-[10px] font-medium" :style="open ? 'color:var(--upf-neon-blue)' : ''">Notifs</span>
                    @if(($unreadNotificationsCount ?? 0) > 0)
                    <span class="notif-badge absolute top-0 right-1 w-4 h-4 rounded-full text-white text-[9px] font-bold flex items-center justify-center">{{ $unreadNotificationsCount }}</span>
                    @endif
                </button>

                {{-- Panel dropdown --}}
                <div x-show="open" x-transition @click.away="open=false"
                     class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden"
                     style="display:none">

                    {{-- Header --}}
                    <div class="flex items-center justify-between px-5 pt-5 pb-3">
                        <h3 class="text-lg font-bold text-slate-800">Notifications</h3>
                        <a href="{{ route('notifications.index') }}"
                           class="text-xs font-semibold text-cyan-600 hover:text-cyan-700 transition">Voir tout</a>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex px-5 mb-2 space-x-2">
                        <button @click="tab='all'"
                                :class="tab==='all' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-500 hover:bg-slate-50'"
                                class="px-4 py-1.5 rounded-full text-sm transition">Tout</button>
                        <button @click="tab='unread'"
                                :class="tab==='unread' ? 'bg-slate-100 text-slate-800 font-semibold' : 'text-slate-500 hover:bg-slate-50'"
                                class="px-4 py-1.5 rounded-full text-sm transition">Non lu</button>
                    </div>

                    {{-- Liste --}}
                    <div class="max-h-96 overflow-y-auto">
                        @forelse(($navbarNotifications ?? collect()) as $notif)
                        @php
                            $isUnread = is_null($notif->read_at);
                            $show = true;
                        @endphp
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           x-show="tab==='all' || (tab==='unread' && {{ $isUnread ? 'true' : 'false' }})"
                           class="flex items-start px-4 py-3 hover:bg-slate-50 transition {{ $isUnread ? 'bg-cyan-50/40' : '' }}">
                            {{-- Avatar --}}
                            <div class="flex-shrink-0 mr-3">
                                @if($notif->data['sender_avatar'] ?? false)
                                    <img src="{{ asset('storage/'.$notif->data['sender_avatar']) }}"
                                         class="w-11 h-11 rounded-full object-cover" alt="">
                                @else
                                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                         style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                                        {{ substr($notif->data['sender_name'] ?? '?', 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            {{-- Contenu --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-700 leading-snug {{ $isUnread ? 'font-medium' : '' }}">
                                    {{ $notif->data['message'] ?? 'Nouvelle notification' }}
                                </p>
                                <p class="text-xs text-cyan-600 font-medium mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            {{-- Point bleu si non lu --}}
                            @if($isUnread)
                            <div class="flex-shrink-0 ml-2 mt-2 w-2.5 h-2.5 rounded-full bg-cyan-500"></div>
                            @endif
                        </a>
                        @empty
                        <div class="flex flex-col items-center py-10 text-center">
                            <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-500">Aucune notification</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Footer --}}
                    @if(($unreadNotificationsCount ?? 0) > 0)
                    <div class="border-t border-slate-100 p-3">
                        <form method="POST" action="{{ route('notifications.readAll') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-sm text-cyan-600 font-semibold hover:bg-cyan-50 py-2 rounded-xl transition">
                                Tout marquer comme lu
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

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
                    <a href="{{ route('suggestions.profiles') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Suggestions de profil
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
    @php
        $isAdminPage    = request()->routeIs('admin.*');
        $isDashboard    = request()->routeIs('dashboard');
        $showSidebars   = $isDashboard && !$isAdminPage;
        $hideRightSidebar = !$showSidebars;
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- LEFT SIDEBAR - Only on dashboard or admin -->
        @if($isAdminPage)
        <!-- Admin Sidebar with Quick Actions -->
        <div class="hidden lg:block lg:col-span-1 anim-slide-left">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                    <h3 class="text-white font-semibold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        ⚡ Actions Rapides
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition p-3 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <span class="font-medium">👥 Utilisateurs</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-sm text-gray-700 hover:text-green-600 hover:bg-green-50 transition p-3 rounded-lg">
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="font-medium">🏠 Retour au site</span>
                    </a>
                    <a href="{{ route('posts.index') }}" class="flex items-center space-x-3 text-sm text-gray-700 hover:text-purple-600 hover:bg-purple-50 transition p-3 rounded-lg">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <span class="font-medium">📝 Posts</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="flex items-center space-x-3 text-sm text-gray-700 hover:text-orange-600 hover:bg-orange-50 transition p-3 rounded-lg">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="font-medium">📅 Événements</span>
                    </a>
                </div>
            </div>
        </div>
        @elseif($showSidebars)
        <!-- Regular User Sidebar -->
        <div class="hidden lg:block lg:col-span-1 anim-slide-left">
            <div class="upf-card overflow-hidden sticky top-24">
                <div class="profile-banner h-20 relative">
                    <div class="absolute inset-0 opacity-20" style="background:repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,.1) 10px,rgba(255,255,255,.1) 20px)"></div>
                </div>
                <div class="px-4 pb-4">
                    <div class="flex flex-col items-center">
                        <img class="w-16 h-16 rounded-full border-4 border-white object-cover -mt-8 shadow-lg relative z-10"
                             src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=1a3a6b&color=fff' }}"
                             alt="">
                        <div class="text-center mt-3">
                            <h2 class="font-bold text-gray-900 text-sm">{{ auth()->user()->name }}</h2>
                            <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->bio ?? auth()->user()->department }}</p>
                            <span class="inline-block mt-2 text-[10px] font-semibold px-2 py-0.5 rounded-full"
                                  style="background:rgba(26,58,107,0.1); color:var(--upf-blue)">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
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
        @endif

        <!-- CENTER CONTENT -->
        <div class="{{ $isAdminPage ? 'lg:col-span-3' : ($showSidebars ? 'lg:col-span-2' : 'lg:col-span-4') }} anim-fade-up">
            {{ $slot }}
        </div>

        <!-- RIGHT SIDEBAR - Only on dashboard -->
        @if($showSidebars)
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
                        @forelse($sidebarUpcomingEvents ?? [] as $event)
                        <li class="border-b border-gray-50 pb-3 {{ $loop->last ? 'border-0 pb-0' : '' }}">
                            <a href="{{ route('events.show', $event) }}" class="text-sm font-medium text-gray-800 hover:text-blue-700 transition leading-tight block">{{ $event->title }}</a>
                            <p class="text-xs text-gray-400 mt-1">{{ $event->event_date->format('d M Y') }} • {{ $event->registrations_count }} inscrits</p>
                        </li>
                        @empty
                        <li class="border-b border-gray-50 pb-3">
                            <span class="text-sm text-gray-500">Aucun événement à venir</span>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Trending Posts -->
            @if(($sidebarTrendingPosts ?? collect())->isNotEmpty())
            <div class="upf-card overflow-hidden mb-4">
                <div class="h-2" style="background:linear-gradient(90deg,var(--upf-gold),var(--upf-red))"></div>
                <div class="p-4">
                    <h3 class="font-bold text-sm text-gray-800 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2" style="color:var(--upf-gold)" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Tendances
                    </h3>
                    <ul class="space-y-3">
                        @foreach($sidebarTrendingPosts as $post)
                        <li class="border-b border-gray-50 pb-3 {{ $loop->last ? 'border-0 pb-0' : '' }}">
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-800 hover:text-blue-700 transition leading-tight block">{{ $post->title ?? Str::limit($post->content, 40) }}</a>
                            <p class="text-xs text-gray-400 mt-1">{{ $post->likes_count }} likes • {{ $post->user->name }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Quick stats -->
            <div class="upf-card p-4">
                <h3 class="font-bold text-sm text-gray-800 mb-3">Votre activité</h3>
                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-lg p-3 text-center" style="background:rgba(26,58,107,0.06)">
                        <div class="font-bold text-lg" style="color:var(--upf-blue)">{{ $userConnectionsCount ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Relations</div>
                    </div>
                    <div class="rounded-lg p-3 text-center" style="background:rgba(192,0,29,0.06)">
                        <div class="font-bold text-lg" style="color:var(--upf-red)">{{ $userPostsCount ?? 0 }}</div>
                        <div class="text-xs text-gray-500">Posts</div>
                    </div>
                </div>
            </div>

            <footer class="mt-4 text-center text-xs text-gray-400 space-x-2">
                <span>&copy; 2026 UPFConnect</span>
                <span>•</span>
                <a href="#" class="hover:text-gray-600">Politique</a>
                <span>•</span>
                <a href="#" class="hover:text-gray-600">Aide</a>
            </footer>
        </div>
        @endif
    </div>
</main>
</body>
</html>
