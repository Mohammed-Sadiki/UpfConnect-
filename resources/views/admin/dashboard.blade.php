<x-app-layout>
    <div class="glass-card rounded-xl shadow-sm p-6 max-w-4xl mx-auto mt-10">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Panneau d'Administration</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-4 text-center">
                <div class="text-blue-600 dark:text-blue-400 text-3xl font-bold mb-1">{{ $stats['users_count'] }}</div>
                <div class="text-xs text-blue-800 dark:text-blue-300 uppercase font-semibold">Utilisateurs</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-xl p-4 text-center">
                <div class="text-green-600 dark:text-green-400 text-3xl font-bold mb-1">{{ $stats['active_students'] }}</div>
                <div class="text-xs text-green-800 dark:text-green-300 uppercase font-semibold">Étudiants Actifs</div>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800 rounded-xl p-4 text-center">
                <div class="text-purple-600 dark:text-purple-400 text-3xl font-bold mb-1">{{ $stats['posts_count'] }}</div>
                <div class="text-xs text-purple-800 dark:text-purple-300 uppercase font-semibold">Publications</div>
            </div>
            <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800 rounded-xl p-4 text-center">
                <div class="text-orange-600 dark:text-orange-400 text-3xl font-bold mb-1">{{ $stats['events_count'] }}</div>
                <div class="text-xs text-orange-800 dark:text-orange-300 uppercase font-semibold">Événements</div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-neutral-700 pb-2">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Gestion rapide</h3>
            <a href="{{ route('admin.users') }}" class="text-sm text-blue-600 hover:underline">Gérer tous les utilisateurs →</a>
        </div>
        
        <p class="text-sm text-gray-500">Le panneau complet de gestion permet de modifier les rôles, désactiver des comptes et modérer les publications (non implémenté dans cet aperçu).</p>
    </div>
</x-app-layout>
