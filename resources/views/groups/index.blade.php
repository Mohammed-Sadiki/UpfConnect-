<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Groupes</h1>
                <p class="text-slate-500 mt-1">Rejoignez des communautés qui partagent vos intérêts</p>
            </div>
            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-all duration-200 shadow-lg shadow-cyan-500/25">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Créer un groupe
            </a>
        </div>

        {{-- Mes Groupes --}}
        @if($myGroups->count() > 0)
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Mes groupes
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($myGroups as $group)
                        <a href="{{ route('groups.show', $group) }}" class="group block glass-card p-4 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if($group->image)
                                        <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->name }}" class="w-14 h-14 rounded-xl object-cover shadow-sm">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-sm">
                                            <span class="text-white text-xl font-bold">{{ substr($group->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-slate-800 truncate group-hover:text-cyan-600 transition-colors">{{ $group->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $group->members_count }} membres</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1
                                        @if($group->category === 'academic') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                        @elseif($group->category === 'club') bg-green-100 text-green-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                        @elseif($group->category === 'project') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                        @elseif($group->category === 'career') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @else bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300
                                        @endif">
                                        {{ $categories[$group->category] ?? $group->category }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Filtres et recherche --}}
        <div class="glass-card p-4 shadow-sm mb-6">
            <form method="GET" action="{{ route('groups.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom du groupe..." class="w-full rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Catégorie</label>
                    <select name="category" class="rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                        <option value="">Toutes</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Visibilité</label>
                    <select name="visibility" class="rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                        <option value="">Tous</option>
                        <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Privé</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'category', 'visibility']))
                    <a href="{{ route('groups.index') }}" class="px-4 py-2 text-slate-600 hover:text-slate-800">
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        {{-- Liste des groupes --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($groups as $group)
                <div class="glass-card overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    {{-- Cover/Header --}}
                    <div class="h-24 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 relative">
                        @if($group->visibility === 'private')
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 text-slate-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Privé
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 -mt-10">
                                @if($group->image)
                                    <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->name }}" class="w-16 h-16 rounded-xl object-cover border-4 border-white shadow-lg">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center border-4 border-white shadow-lg">
                                        <span class="text-white text-2xl font-bold">{{ substr($group->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 pt-1">
                                <h3 class="text-base font-bold text-slate-800 truncate">{{ $group->name }}</h3>
                                <p class="text-xs text-slate-500">par {{ $group->creator->name }}</p>
                            </div>
                        </div>

                        <p class="mt-3 text-sm text-slate-600 line-clamp-2">{{ Str::limit($group->description, 100) }}</p>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-3 text-xs text-slate-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    {{ $group->approved_members_count }} membres
                                </span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($group->category === 'academic') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                @elseif($group->category === 'club') bg-green-100 text-green-800 dark:bg-emerald-900/30 dark:text-emerald-300
                                @elseif($group->category === 'project') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300
                                @elseif($group->category === 'career') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                @else bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300
                                @endif">
                                {{ $categories[$group->category] ?? $group->category }}
                            </span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <a href="{{ route('groups.show', $group) }}" class="block w-full text-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-all duration-200 shadow-lg shadow-cyan-500/25">
                                Voir le groupe
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">Aucun groupe trouvé</h3>
                    <p class="mt-2 text-slate-500">Essayez d'ajuster vos filtres ou créez un nouveau groupe !</p>
                    <a href="{{ route('groups.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors">
                        Créer un groupe
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($groups->hasPages())
            <div class="mt-8">
                {{ $groups->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
