<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UPFConnect') }} — Réseau social universitaire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'system-ui', 'sans-serif'] },
                    colors: {
                        upf: { navy: '#1a3a6b', royal: '#2563eb', soft: '#e0e7ff' }
                    },
                    animation: {
                        float: 'float 5s ease-in-out infinite',
                        'float-delayed': 'float 5s ease-in-out 1.2s infinite',
                        'fade-up': 'fadeUp 0.7s ease-out forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-14px)' },
                        },
                        fadeUp: {
                            from: { opacity: '0', transform: 'translateY(24px)' },
                            to: { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Poppins', system-ui, sans-serif; }
        .glass-header {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 4px 24px rgba(37, 99, 235, 0.08);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 8px 32px rgba(26, 58, 107, 0.06);
        }
        .hero-gradient {
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(139, 92, 246, 0.18), transparent),
                radial-gradient(ellipse 70% 50% at 90% 10%, rgba(37, 99, 235, 0.15), transparent),
                radial-gradient(ellipse 60% 40% at 50% 100%, rgba(14, 165, 233, 0.12), transparent),
                linear-gradient(180deg, #f8fafc 0%, #eef2ff 45%, #f1f5f9 100%);
        }
        .btn-gradient {
            background: linear-gradient(105deg, #7c3aed 0%, #4f46e5 45%, #2563eb 100%);
        }
        .cube-glow {
            filter: drop-shadow(0 0 40px rgba(59, 130, 246, 0.35)) drop-shadow(0 0 60px rgba(139, 92, 246, 0.25));
        }
    </style>
</head>
<body class="antialiased text-slate-800 min-h-screen hero-gradient">

    <header class="sticky top-0 z-50 px-4 sm:px-6 lg:px-8 pt-6">
        <div class="max-w-6xl mx-auto glass-header rounded-2xl px-5 py-4 flex flex-wrap items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-upf-navy leading-tight">UPFConnect</p>
                    <p class="text-xs font-medium text-blue-600 tracking-wide">Réseau Social Universitaire</p>
                </div>
            </a>
            <nav class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-upf-navy bg-white border border-slate-200 shadow-sm hover:border-blue-300 hover:shadow-md transition-all">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white btn-gradient shadow-lg shadow-indigo-500/30 hover:opacity-95 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                    S'inscrire
                </a>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 pt-10 lg:pt-14">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            <div class="order-2 lg:order-1 space-y-8 animate-[fadeUp_0.7s_ease-out_forwards]">
                <h1 class="text-3xl sm:text-4xl lg:text-[2.65rem] font-extrabold text-upf-navy leading-[1.15] tracking-tight">
                    Connectez-vous avec votre communauté
                </h1>
                <p class="text-base sm:text-lg text-slate-600 max-w-xl leading-relaxed">
                    La plateforme dédiée aux étudiants et enseignants pour partager, apprendre et évoluer ensemble.
                </p>
                <div class="flex flex-col sm:flex-row flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl text-white font-semibold text-sm btn-gradient shadow-lg shadow-violet-500/25 hover:-translate-y-0.5 transition-transform">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Rejoindre maintenant
                    </a>
                    <a href="#stats"
                       class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl font-semibold text-sm text-upf-navy bg-white/90 border border-slate-200 shadow-sm hover:bg-white hover:border-blue-200 transition-colors">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                            <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        En savoir plus
                    </a>
                </div>
            </div>

            <div class="order-1 lg:order-2 relative flex justify-center lg:justify-end">
                <div class="absolute inset-0 bg-gradient-to-tr from-violet-200/40 to-blue-200/30 blur-3xl rounded-full scale-90 pointer-events-none" aria-hidden="true"></div>
                <div class="relative w-full max-w-md aspect-square flex items-center justify-center cube-glow">
                    <!-- Cube wireframe + cap -->
                    <svg class="w-full h-auto max-h-[340px] text-blue-500/90" viewBox="0 0 320 320" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <defs>
                            <linearGradient id="g1" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.9"/>
                                <stop offset="100%" stop-color="#a78bfa" stop-opacity="0.7"/>
                            </linearGradient>
                        </defs>
                        <g opacity="0.35" stroke="currentColor" stroke-width="1.2">
                            <path d="M80 200 L160 240 L240 200 L160 160 Z"/>
                            <path d="M80 120 L160 160 L240 120 L160 80 Z"/>
                            <path d="M80 120 L80 200"/><path d="M160 80 L160 160"/><path d="M240 120 L240 200"/>
                            <path d="M80 120 L160 80 L240 120"/><path d="M160 80 L160 160"/><path d="M80 120 L160 160"/><path d="M240 120 L160 160"/>
                        </g>
                        <rect x="100" y="100" width="120" height="120" rx="12" fill="url(#g1)" fill-opacity="0.15" stroke="url(#g1)" stroke-width="2"/>
                        <path transform="translate(118, 95)" fill="#7c3aed" d="M42 8L12 22v18c0 18 12 34 30 38 18-4 30-20 30-38V22L42 8zm0 54c-8.3 0-15-2.7-15-6h30c0 3.3-6.7 6-15 6z"/>
                    </svg>
                    <!-- Floating icons -->
                    <div class="absolute top-[8%] right-[5%] w-14 h-14 rounded-2xl bg-white/80 backdrop-blur border border-white shadow-lg flex items-center justify-center text-violet-600 animate-float" aria-hidden="true">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <div class="absolute bottom-[18%] left-[0%] w-12 h-12 rounded-xl bg-white/80 backdrop-blur border border-white shadow-lg flex items-center justify-center text-blue-600 animate-float-delayed" aria-hidden="true">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/></svg>
                    </div>
                    <div class="absolute top-[40%] -left-[2%] w-11 h-11 rounded-xl bg-white/75 backdrop-blur border border-white shadow-md flex items-center justify-center text-indigo-500 animate-float" style="animation-delay: 0.6s" aria-hidden="true">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <section id="stats" class="mt-20 lg:mt-24 grid sm:grid-cols-3 gap-6 scroll-mt-28">
            <article class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 to-violet-400 rounded-b-2xl"></div>
                <div class="w-12 h-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <p class="text-3xl font-bold text-upf-navy">36+</p>
                <p class="text-xs font-bold tracking-widest text-slate-500 mt-1">MEMBRES</p>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">Une communauté active et engagée.</p>
            </article>
            <article class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-sky-400 rounded-b-2xl"></div>
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
                </div>
                <p class="text-3xl font-bold text-upf-navy">50+</p>
                <p class="text-xs font-bold tracking-widest text-slate-500 mt-1">PUBLICATIONS</p>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">Contenus partagés chaque semaine.</p>
            </article>
            <article class="glass-card rounded-2xl p-6 relative overflow-hidden group hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-violet-500 to-indigo-500 rounded-b-2xl"></div>
                <div class="w-12 h-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5z"/></svg>
                </div>
                <p class="text-3xl font-bold text-upf-navy">20+</p>
                <p class="text-xs font-bold tracking-widest text-slate-500 mt-1">ÉVÉNEMENTS</p>
                <p class="text-sm text-slate-600 mt-3 leading-relaxed">Activités et rencontres enrichissantes.</p>
            </article>
        </section>

        <footer class="mt-16 glass-card rounded-3xl px-6 py-8 sm:px-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-upf-navy font-bold text-sm border border-slate-200">UPF</div>
                    <div>
                        <p class="font-bold text-upf-navy">UPF University</p>
                        <p class="text-xs text-slate-500">Partenaire académique</p>
                    </div>
                </div>
                <blockquote class="lg:flex-1 lg:px-8 text-center lg:text-left">
                    <p class="text-sm sm:text-base italic text-violet-700 font-medium leading-relaxed">
                        « L'éducation est l'arme la plus puissante pour changer le monde. »
                        <span class="not-italic text-slate-500 text-sm block mt-1">— Nelson Mandela</span>
                    </p>
                </blockquote>
                <div class="flex justify-center lg:justify-end gap-3">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 flex items-center justify-center transition-colors" aria-label="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-sky-500 hover:text-white text-slate-600 flex items-center justify-center transition-colors" aria-label="Twitter">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-blue-700 hover:text-white text-slate-600 flex items-center justify-center transition-colors" aria-label="LinkedIn">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-100 hover:bg-gradient-to-br hover:from-purple-600 hover:to-pink-500 hover:text-white text-slate-600 flex items-center justify-center transition-colors" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>
            <p class="text-center text-xs text-slate-400 mt-8 pt-6 border-t border-slate-100">
                © {{ date('Y') }} UPFConnect. Tous droits réservés.
            </p>
        </footer>
    </main>
</body>
</html>
