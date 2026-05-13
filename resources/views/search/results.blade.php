<x-app-layout>
    <div class="mx-auto max-w-4xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="mb-6 text-xl font-bold text-slate-900 sm:text-2xl" style="color: #0f172a;">
            Résultats de recherche pour « {{ $query }} »
        </h1>

        @if(session('error'))
            <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4">
                <p class="text-sm font-medium text-amber-900">{{ session('error') }}</p>
            </div>
        @endif

        <div class="mb-6 border-b border-slate-200">
            <nav class="flex flex-wrap gap-x-6 gap-y-1" aria-label="Filtrer les résultats">
                <button type="button" onclick="showTab('users')" id="tab-users" class="tab-btn border-b-2 border-blue-600 py-2 text-sm font-semibold text-blue-600">
                    Utilisateurs ({{ $users->count() }})
                </button>
                <button type="button" onclick="showTab('posts')" id="tab-posts" class="tab-btn border-b-2 border-transparent py-2 text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Posts ({{ $posts->count() }})
                </button>
                <button type="button" onclick="showTab('events')" id="tab-events" class="tab-btn border-b-2 border-transparent py-2 text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Événements ({{ $events->count() }})
                </button>
            </nav>
        </div>

        <div id="content-users" class="tab-content">
            @if($users->isEmpty())
                <p class="py-10 text-center text-sm font-medium text-slate-600">Aucun utilisateur trouvé.</p>
            @else
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach($users as $user)
                        <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50/80 p-4 transition hover:border-slate-300 hover:bg-white">
                            <a href="{{ route('profile.show', $user) }}" class="shrink-0">
                                <img class="h-14 w-14 rounded-full object-cover ring-2 ring-white"
                                    src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=2563eb&color=fff' }}"
                                    alt="">
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('profile.show', $user) }}" class="block truncate text-base font-semibold text-slate-900 hover:text-blue-600" style="color: #0f172a;">{{ $user->name }}</a>
                                <p class="mt-0.5 truncate text-sm text-slate-600">{{ $user->department ?? 'Membre UPFConnect' }}</p>
                                <span class="mt-2 inline-block rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-semibold text-blue-800">{{ ucfirst($user->role) }}</span>
                            </div>
                            <form action="{{ route('connections.request', $user) }}" method="POST" class="shrink-0">
                                @csrf
                                <button type="submit" class="rounded-full bg-blue-600 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-sm transition hover:bg-blue-700">
                                    Se connecter
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="content-posts" class="tab-content hidden">
            @if($posts->isEmpty())
                <p class="py-10 text-center text-sm font-medium text-slate-600">Aucun post trouvé.</p>
            @else
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <article class="rounded-xl border border-slate-200 bg-slate-50/80 p-5 transition hover:border-slate-300 hover:bg-white">
                            <div class="mb-3 flex items-center gap-3">
                                <img class="h-10 w-10 shrink-0 rounded-full object-cover ring-2 ring-white"
                                    src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=2563eb&color=fff' }}"
                                    alt="">
                                <div class="min-w-0">
                                    <a href="{{ route('profile.show', $post->user) }}" class="text-sm font-semibold text-slate-900 hover:text-blue-600" style="color: #0f172a;">{{ $post->user->name }}</a>
                                    <p class="text-xs font-medium text-slate-500">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($post->title)
                                <p class="mb-2 text-base font-semibold leading-snug text-slate-900" style="color: #0f172a;">{{ $post->title }}</p>
                            @endif
                            <p class="text-sm leading-relaxed text-slate-700">{{ Str::limit($post->content, 200) }}</p>
                            <a href="{{ route('dashboard') }}#post-{{ $post->id }}" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                                Voir le post
                                <span aria-hidden="true">→</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="content-events" class="tab-content hidden">
            @if($events->isEmpty())
                <p class="py-10 text-center text-sm font-medium text-slate-600">Aucun événement trouvé.</p>
            @else
                <div class="space-y-4">
                    @foreach($events as $event)
                        <div class="flex items-start gap-4 rounded-xl border border-slate-200 bg-slate-50/80 p-4 transition hover:border-slate-300 hover:bg-white">
                            <div class="flex h-16 w-16 shrink-0 flex-col items-center justify-center rounded-lg border border-blue-200 bg-blue-50">
                                <span class="text-lg font-bold text-blue-700">{{ $event->event_date->format('d') }}</span>
                                <span class="text-[10px] font-bold uppercase text-blue-600">{{ $event->event_date->translatedFormat('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('events.show', $event) }}" class="block font-semibold text-slate-900 hover:text-blue-600" style="color: #0f172a;">{{ $event->title }}</a>
                                <p class="mt-1 text-sm text-slate-600">📍 {{ $event->location }}</p>
                                <p class="mt-2 text-sm leading-relaxed text-slate-700">{{ Str::limit($event->description, 100) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(function (el) {
                el.classList.add('hidden');
            });
            document.querySelectorAll('.tab-btn').forEach(function (el) {
                el.classList.remove('border-blue-600', 'text-blue-600');
                el.classList.add('border-transparent', 'text-slate-600');
            });
            document.getElementById('content-' + tabName).classList.remove('hidden');
            var activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-slate-600');
            activeTab.classList.add('border-blue-600', 'text-blue-600');
        }
    </script>
</x-app-layout>
