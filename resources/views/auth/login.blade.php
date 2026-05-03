<x-guest-layout>
    <div>
        <h2 class="text-2xl font-bold mb-1" style="color:#1a3a6b">Bienvenue 👋</h2>
        <p class="text-sm text-gray-500 mb-6">Connectez-vous à votre espace UniConnect</p>

        @if(session('status'))
        <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5">Adresse e-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 transition @error('email') border-red-400 @enderror"
                    placeholder="exemple@uniconnect.edu">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="flex justify-between mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">Mot de passe</label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium hover:underline" style="color:#1a3a6b">Oublié ?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-600 transition @error('password') border-red-400 @enderror"
                    placeholder="••••••••">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300" style="accent-color:#1a3a6b">
                <label for="remember_me" class="ml-2 text-sm text-gray-600">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-shine w-full py-3 text-white font-semibold rounded-xl text-sm transition shadow-lg hover:shadow-xl hover:-translate-y-0.5 transform" style="background:linear-gradient(135deg,#1a3a6b,#2952a3)">
                Se connecter
            </button>
        </form>

        @if(Route::has('register'))
        <p class="mt-6 text-center text-sm text-gray-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:#c0001d">S'inscrire</a>
        </p>
        @endif

        <!-- Demo accounts -->
        <div class="mt-6 pt-5 border-t border-gray-100">
            <p class="text-[10px] text-center text-gray-400 uppercase tracking-widest font-semibold mb-3">Connexion rapide (démo)</p>
            <div class="grid grid-cols-3 gap-2">
                <button onclick="setLogin('admin@uniconnect.edu')"
                    class="py-2.5 rounded-xl text-xs font-semibold border-2 transition hover:-translate-y-0.5 transform"
                    style="background:rgba(26,58,107,0.06);border-color:#1a3a6b;color:#1a3a6b">
                    🔐 Admin
                </button>
                <button onclick="setLogin('teacher1@uniconnect.edu')"
                    class="py-2.5 rounded-xl text-xs font-semibold border-2 transition hover:-translate-y-0.5 transform"
                    style="background:rgba(192,0,29,0.06);border-color:#c0001d;color:#c0001d">
                    👨‍🏫 Enseignant
                </button>
                <button onclick="setLogin('student1@uniconnect.edu')"
                    class="py-2.5 rounded-xl text-xs font-semibold border-2 transition hover:-translate-y-0.5 transform"
                    style="background:rgba(232,160,32,0.1);border-color:#e8a020;color:#b07010">
                    🎓 Étudiant
                </button>
            </div>
        </div>
    </div>
    <script>
        function setLogin(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
        }
    </script>
</x-guest-layout>
