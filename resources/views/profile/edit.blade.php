<x-app-layout>
    <div class="glass-card rounded-xl shadow-sm p-6 max-w-2xl mx-auto mt-10">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Modifier le profil</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex items-center space-x-6 mb-6">
                <div class="shrink-0">
                    <img class="h-16 w-16 object-cover rounded-full" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" alt="Avatar">
                </div>
                <label class="block">
                    <span class="sr-only">Choisir un avatar</span>
                    <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:text-gray-300"/>
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bio / Titre</label>
                <textarea name="bio" rows="2" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL LinkedIn</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $user->profile->linkedin_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL GitHub</label>
                    <input type="url" name="github_url" value="{{ old('github_url', $user->profile->github_url ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Compétences (séparées par des virgules)</label>
                <input type="text" name="skills" value="{{ old('skills', is_array($user->profile->skills ?? null) ? implode(',', $user->profile->skills) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="PHP, Laravel, JavaScript">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intérêts (séparées par des virgules)</label>
                <input type="text" name="interests" value="{{ old('interests', is_array($user->profile->interests ?? null) ? implode(',', $user->profile->interests) : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Intelligence Artificielle, Web3">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload CV (PDF uniquement)</label>
                <input type="file" name="cv" accept=".pdf" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300">
                @if($user->profile->cv_path ?? false)
                    <p class="text-xs text-green-600 mt-1">CV actuel en ligne.</p>
                @endif
            </div>

            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('profile.show', $user) }}" class="bg-gray-200 dark:bg-neutral-700 hover:bg-gray-300 dark:hover:bg-neutral-600 text-gray-800 dark:text-white font-semibold py-2 px-4 rounded-lg transition">Annuler</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">Enregistrer</button>
            </div>
        </form>
    </div>
</x-app-layout>
