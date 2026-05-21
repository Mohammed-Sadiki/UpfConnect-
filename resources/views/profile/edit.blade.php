<x-app-layout>
    <div class="mx-auto mt-8 max-w-2xl glass-card p-6 shadow-sm sm:p-8">
        <h2 class="mb-6 text-2xl font-bold text-slate-900">Modifier le profil</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="mb-2 flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="shrink-0">
                    <img class="h-20 w-20 rounded-full border-2 border-slate-200 object-cover" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e2e8f0&color=0f172a&size=128' }}" alt="Avatar">
                </div>
                <label class="block w-full min-w-0">
                    <span class="mb-1.5 block text-sm font-semibold text-slate-800">Photo de profil</span>
                    <input type="file" name="avatar" accept="image/*"
                        class="block w-full cursor-pointer text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700">
                </label>
            </div>

            <div>
                <label for="edit-name" class="mb-1.5 block text-sm font-semibold text-slate-800">Nom complet</label>
                <input id="edit-name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="mt-0 block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm text-slate-900 dark:text-slate-100 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div>
                <label for="edit-bio" class="mb-1.5 block text-sm font-semibold text-slate-800">Bio / Titre</label>
                <textarea id="edit-bio" name="bio" rows="4"
                    class="mt-0 block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm text-slate-900 dark:text-slate-100 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="edit-linkedin" class="mb-1.5 block text-sm font-semibold text-slate-800">URL LinkedIn</label>
                    <input id="edit-linkedin" type="url" name="linkedin_url" value="{{ old('linkedin_url', optional($user->profile)->linkedin_url ?? '') }}"
                        class="mt-0 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                        placeholder="https://linkedin.com/in/...">
                </div>
                <div>
                    <label for="edit-github" class="mb-1.5 block text-sm font-semibold text-slate-800">URL GitHub</label>
                    <input id="edit-github" type="url" name="github_url" value="{{ old('github_url', optional($user->profile)->github_url ?? '') }}"
                        class="mt-0 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                        placeholder="https://github.com/...">
                </div>
            </div>

            <div>
                <label for="edit-skills" class="mb-1.5 block text-sm font-semibold text-slate-800">Compétences <span class="font-normal text-slate-500">(séparées par des virgules)</span></label>
                <input id="edit-skills" type="text" name="skills" value="{{ old('skills', ($user->profile && is_array($user->profile->skills)) ? implode(',', $user->profile->skills) : '') }}"
                    class="mt-0 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    placeholder="PHP, Laravel, JavaScript">
            </div>

            <div>
                <label for="edit-interests" class="mb-1.5 block text-sm font-semibold text-slate-800">Intérêts <span class="font-normal text-slate-500">(séparés par des virgules)</span></label>
                <input id="edit-interests" type="text" name="interests" value="{{ old('interests', ($user->profile && is_array($user->profile->interests)) ? implode(',', $user->profile->interests) : '') }}"
                    class="mt-0 block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                    placeholder="Intelligence artificielle, Web…">
            </div>

            <div>
                <label for="edit-cv" class="mb-1.5 block text-sm font-semibold text-slate-800">CV <span class="font-normal text-slate-500">(PDF uniquement)</span></label>
                <input id="edit-cv" type="file" name="cv" accept=".pdf"
                    class="mt-0 block w-full cursor-pointer text-sm text-slate-600 file:mr-4 file:cursor-pointer file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-800 hover:file:bg-slate-200">
                @if($user->profile && $user->profile->cv_path)
                    <p class="mt-2 text-xs font-medium text-emerald-700">Un CV est déjà en ligne — en choisir un autre le remplacera.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-6">
                <a href="{{ route('profile.show', $user) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-800 transition hover:bg-slate-50">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
