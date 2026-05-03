<x-app-layout>
    <div class="glass-card rounded-xl shadow-sm p-6 max-w-5xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Utilisateurs</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600">← Retour au Dashboard</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-neutral-800 dark:text-gray-300">
                    <tr>
                        <th scope="col" class="px-6 py-3">Utilisateur</th>
                        <th scope="col" class="px-6 py-3">Département</th>
                        <th scope="col" class="px-6 py-3">Rôle</th>
                        <th scope="col" class="px-6 py-3">Statut</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b dark:border-neutral-700 bg-white dark:bg-neutral-900 hover:bg-gray-50 dark:hover:bg-neutral-800">
                        <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                            <img class="w-10 h-10 rounded-full" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}">
                            <div class="pl-3">
                                <div class="text-base font-semibold">{{ $user->name }}</div>
                                <div class="font-normal text-gray-500">{{ $user->email }}</div>
                            </div>
                        </th>
                        <td class="px-6 py-4">
                            {{ $user->department }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="flex items-center space-x-2">
                                @csrf @method('PATCH')
                                <select name="role" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-1.5 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white">
                                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Étudiant</option>
                                    <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }}>Professeur</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-2.5 w-2.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }} mr-2"></div>
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="font-medium {{ $user->is_active ? 'text-red-600 dark:text-red-500 hover:underline' : 'text-green-600 dark:text-green-500 hover:underline' }}">
                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
