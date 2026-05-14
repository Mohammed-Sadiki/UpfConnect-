<x-guest-layout>
    @include('auth.partials.brand-header', ['showTagline' => false])

    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900">Vérifiez votre e-mail</h2>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">
            Merci pour votre inscription. Avant de continuer, veuillez confirmer votre adresse en cliquant sur le lien que nous venons de vous envoyer.
            Si vous n’avez rien reçu, vous pouvez demander un nouvel e-mail.
        </p>
    </div>

    @if(session('status'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#4A6CF7] to-[#7E3AF2] py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-[0.97] sm:w-auto sm:px-6">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Renvoyer l’e-mail de confirmation
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="shrink-0 text-center sm:text-right">
            @csrf
            <button type="submit" class="text-sm font-semibold text-slate-500 underline decoration-slate-300 underline-offset-2 transition hover:text-slate-800">
                Se déconnecter
            </button>
        </form>
    </div>

    <p class="mt-8 rounded-xl border border-slate-100 bg-slate-50/80 px-4 py-3 text-center text-xs text-slate-500">
        Pensez à vérifier vos courriers indésirables. Le lien expire après un certain temps pour des raisons de sécurité.
    </p>
</x-guest-layout>
