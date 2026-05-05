<x-app-layout>
    <div class="glass-card rounded-xl p-6 shadow-sm">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
            Résultats de recherche pour "{{ $query }}"
        </h1>

        @if(session('error'))
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Onglets -->
        <div class="border-b border-gray-200 dark:border-neutral-700 mb-6">
            <nav class="flex space-x-6">
                <button onclick="showTab('users')" id="tab-users" class="tab-btn py-2 px-1 border-b-2 border-blue-500 text-blue-600 font-medium text-sm">
                    Utilisateurs ({{ $users->count() }})
                </button>
                <button onclick="showTab('posts')" id="tab-posts" class="tab-btn py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Posts ({{ $posts->count() }})
                </button>
                <button onclick="showTab('events')" id="tab-events" class="tab-btn py-2 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm">
                    Événements ({{ $events->count() }})
                </button>
            </nav>
        </div>

        <!-- Résultats Utilisateurs -->
        <div id="content-users" class="tab-content">
            @if($users->isEmpty())
                <p class="text-gray-500 text-center py-8">Aucun utilisateur trouvé</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($users as $user)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-neutral-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition">
                        <a href="{{ route('profile.show', $user) }}">
                            <img class="h-14 w-14 rounded-full object-cover"
                                 src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=3b82f6&color=fff' }}"
                                 alt="">
                        </a>
                        <div class="flex-1">
                            <a href="{{ route('profile.show', $user) }}" class="font-semibold text-gray-900 dark:text-white hover:text-blue-600">{{ $user->name }}</a>
                            <p class="text-sm text-gray-500">{{ $user->department ?? 'Membre UPFConnect' }}</p>
                            <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <form action="{{ route('connections.request', $user) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary text-sm py-2 px-4">
                                Se connecter
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Résultats Posts -->
        <div id="content-posts" class="tab-content hidden">
            @if($posts->isEmpty())
                <p class="text-gray-500 text-center py-8">Aucun post trouvé</p>
            @else
                <div class="space-y-4">
                    @foreach($posts as $post)
                    <div class="p-4 bg-gray-50 dark:bg-neutral-800 rounded-lg">
                        <div class="flex items-center space-x-3 mb-3">
                            <img class="h-10 w-10 rounded-full object-cover"
                                 src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=3b82f6&color=fff' }}"
                                 alt="">
                            <div>
                                <a href="{{ route('profile.show', $post->user) }}" class="font-semibold text-sm text-gray-900 dark:text-white hover:text-blue-600">{{ $post->user->name }}</a>
                                <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($post->title)
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $post->title }}</h3>
                        @endif
                        <p class="text-gray-700 dark:text-gray-300 text-sm">{{ Str::limit($post->content, 200) }}</p>
                        <a href="{{ route('dashboard') }}#post-{{ $post->id }}" class="text-blue-600 text-sm mt-2 inline-block hover:underline">Voir le post →</a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Résultats Événements -->
        <div id="content-events" class="tab-content hidden">
            @if($events->isEmpty())
                <p class="text-gray-500 text-center py-8">Aucun événement trouvé</p>
            @else
                <div class="space-y-4">
                    @foreach($events as $event)
                    <div class="p-4 bg-gray-50 dark:bg-neutral-800 rounded-lg flex items-start space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-lg flex flex-col items-center justify-center">
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-300">{{ $event->event_date->format('d') }}</span>
                            <span class="text-xs text-blue-500 uppercase">{{ $event->event_date->format('M') }}</span>
                        </div>
                        <div class="flex-1">
                            <a href="{{ route('events.show', $event) }}" class="font-semibold text-gray-900 dark:text-white hover:text-blue-600">{{ $event->title }}</a>
                            <p class="text-sm text-gray-500 mt-1">📍 {{ $event->location }}</p>
                            <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">{{ Str::limit($event->description, 100) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Masquer tout le contenu
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Réinitialiser tous les onglets
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-blue-500', 'text-blue-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            // Afficher le contenu sélectionné
            document.getElementById('content-' + tabName).classList.remove('hidden');
            // Activer l'onglet sélectionné
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-blue-500', 'text-blue-600');
        }
    </script>
</x-app-layout>
