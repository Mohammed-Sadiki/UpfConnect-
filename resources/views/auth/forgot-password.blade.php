<x-guest-layout>
    @include('auth.partials.brand-header')

    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-slate-900">Mot de passe oublié</h2>
        <p class="mt-1 text-sm text-slate-500">Indiquez votre adresse e-mail : nous vous enverrons un lien pour en choisir un nouveau.</p>
    </div>

    @if(session('status'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-xs font-bold text-slate-900">Adresse e-mail</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="votre@email.com"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50/90 py-3 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-violet-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 @error('email') border-red-400 @enderror">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#4A6CF7] to-[#7E3AF2] py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-[0.97] hover:shadow-xl active:scale-[0.99]">
            Envoyer le lien de réinitialisation
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-500">
        <a href="{{ route('login') }}" class="font-semibold text-violet-600 transition hover:text-violet-700 hover:underline">← Retour à la connexion</a>
    </p>
</x-guest-layout>
