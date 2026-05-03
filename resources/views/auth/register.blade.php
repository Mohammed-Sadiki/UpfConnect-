<x-guest-layout>
    <div class="p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Créer un compte 🎓</h2>
        <p class="text-sm text-gray-500 mb-6">Rejoignez la communauté universitaire UniConnect</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition text-sm @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition text-sm @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition text-sm @error('password') border-red-500 @enderror">
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition text-sm @error('password_confirmation') border-red-500 @enderror">
                @error('password_confirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl transition shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2">
                Créer mon compte
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Déjà inscrit ?
            <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Se connecter</a>
        </p>
    </div>
</x-guest-layout>
