<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UPFConnect') }} — {{ $title ?? 'Bienvenue' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, sans-serif; }

        /* Fond de base doux */
        .auth-body {
            min-height: 100vh;
            min-height: 100dvh;
            background: linear-gradient(160deg, #eef4ff 0%, #f5f3ff 45%, #ede9fe 100%);
        }

        /* Couche animée : blobs néon flous */
        .auth-blobs {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .auth-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(72px);
            opacity: 0.55;
            mix-blend-mode: multiply;
            will-change: transform;
        }

        .auth-blob--1 {
            width: min(42rem, 90vw);
            height: min(42rem, 90vw);
            top: -12%;
            left: -8%;
            background: radial-gradient(circle at 30% 30%, rgba(56, 189, 248, 0.95), rgba(99, 102, 241, 0.5) 55%, transparent 70%);
            animation: auth-float-1 22s ease-in-out infinite;
        }

        .auth-blob--2 {
            width: min(38rem, 85vw);
            height: min(38rem, 85vw);
            bottom: -15%;
            right: -10%;
            background: radial-gradient(circle at 60% 40%, rgba(167, 139, 250, 0.9), rgba(14, 165, 233, 0.45) 50%, transparent 72%);
            animation: auth-float-2 26s ease-in-out infinite;
        }

        .auth-blob--3 {
            width: min(28rem, 70vw);
            height: min(28rem, 70vw);
            top: 38%;
            left: 25%;
            background: radial-gradient(circle at 50% 50%, rgba(129, 140, 248, 0.75), rgba(34, 211, 238, 0.35) 60%, transparent 75%);
            animation: auth-float-3 18s ease-in-out infinite;
            opacity: 0.4;
        }

        /* Halo lumineux léger au centre */
        .auth-glow-mesh {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(99, 102, 241, 0.12), transparent 55%),
                radial-gradient(ellipse 60% 45% at 100% 50%, rgba(14, 165, 233, 0.08), transparent 50%),
                radial-gradient(ellipse 50% 40% at 0% 80%, rgba(139, 92, 246, 0.1), transparent 50%);
            animation: auth-mesh-shift 32s ease-in-out infinite alternate;
        }

        @keyframes auth-float-1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            40% { transform: translate(6%, 4%) scale(1.06); }
            70% { transform: translate(-4%, 6%) scale(0.98); }
        }

        @keyframes auth-float-2 {
            0%, 100% { transform: translate(0, 0) scale(1.02); }
            50% { transform: translate(-7%, -5%) scale(1.08); }
        }

        @keyframes auth-float-3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(10%, -6%) scale(1.05); }
            66% { transform: translate(-8%, 4%) scale(0.94); }
        }

        @keyframes auth-mesh-shift {
            0% { opacity: 1; }
            100% { opacity: 0.85; }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-blob,
            .auth-glow-mesh {
                animation: none !important;
            }
            .auth-blob--1 { transform: translate(2%, 2%); }
            .auth-blob--2 { transform: translate(-2%, -2%); }
            .auth-blob--3 { transform: translate(0, 0); }
        }

        /* Carte glassmorphism : lisible sur le fond animé */
        .auth-glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.65);
            box-shadow:
                0 4px 24px rgba(99, 102, 241, 0.08),
                0 24px 64px -12px rgba(79, 70, 229, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body class="auth-body text-slate-800 antialiased overflow-x-hidden">
    <div class="auth-blobs" aria-hidden="true">
        <div class="auth-blob auth-blob--1"></div>
        <div class="auth-blob auth-blob--2"></div>
        <div class="auth-blob auth-blob--3"></div>
    </div>
    <div class="auth-glow-mesh" aria-hidden="true"></div>

    <div class="relative z-10 flex min-h-screen min-h-[100dvh] flex-col">
        <a href="{{ route('home') }}"
            class="shrink-0 self-start px-4 pt-5 text-sm font-medium text-slate-600 transition hover:text-violet-700 sm:px-6 sm:pt-6">
            ← Retour à l'accueil
        </a>

        <div class="flex min-h-0 flex-1 flex-col items-center justify-start overflow-y-auto px-4 pb-10 pt-4 sm:px-6 sm:pb-12 sm:pt-5">
            <div class="auth-glass-card w-full max-w-[420px] rounded-2xl p-8 sm:rounded-[1.25rem] sm:p-10">
                {{ $slot }}
            </div>
        </div>

        <p class="shrink-0 px-4 pb-6 text-center text-xs text-slate-500 sm:px-6">
            © {{ date('Y') }} {{ config('app.name', 'UPFConnect') }}
        </p>
    </div>
</body>
</html>
