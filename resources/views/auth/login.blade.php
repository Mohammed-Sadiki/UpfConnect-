<x-guest-layout>
    @include('auth.partials.brand-header')

    <div class="mb-8 text-center">
        <h2 class="text-xl font-bold text-slate-900">Bienvenue !</h2>
        <p class="mt-1 text-sm text-slate-500">Connectez-vous à votre compte</p>
    </div>

    @if(session('status'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    <div x-data="{ showPassword: false }">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-xs font-bold text-slate-900">Adresse e-mail</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        placeholder="votre@email.com"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/90 py-3 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-violet-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 @error('email') border-red-400 focus:border-red-400 focus:ring-red-500/10 @enderror">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-bold text-slate-900">Mot de passe</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400" aria-hidden="true">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50/90 py-3 pl-11 pr-12 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-violet-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-violet-500/10 @error('password') border-red-400 @enderror">
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 transition hover:text-violet-600" tabindex="-1" :aria-label="showPassword ? 'Masquer le mot de passe' : 'Afficher le mot de passe'">
                        <span x-show="!showPassword" class="flex items-center justify-center"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg></span>
                        <span x-show="showPassword" x-cloak class="flex items-center justify-center"><svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @if(Route::has('password.request'))
                    <div class="mt-2 text-right">
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-violet-600 transition hover:text-violet-700 hover:underline">Mot de passe oublié ?</a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-2.5">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500/30">
                <label for="remember_me" class="text-sm font-medium text-slate-600">Se souvenir de moi</label>
            </div>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#4A6CF7] to-[#7E3AF2] py-3.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/25 transition hover:opacity-[0.97] hover:shadow-xl active:scale-[0.99]">
                Se connecter
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>
    </div>

    @if(Route::has('register'))
        <p class="mt-8 text-center text-sm text-slate-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-violet-600 transition hover:text-violet-700 hover:underline">S'inscrire</a>
        </p>
    @endif
</x-guest-layout>
