<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Groupes suggérés</h1>
                <p class="text-slate-500 mt-1">Découvrez des communautés qui pourraient vous intéresser</p>
            </div>
            <a href="{{ route('groups.index') }}" class="text-cyan-600 hover:text-cyan-700 font-medium">
                Voir tous les groupes
            </a>
        </div>

        {{-- Groupes suggérés --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($suggestedGroups as $group)
                <div class="bg-white/80 backdrop-blur-xl rounded-2xl overflow-hidden border border-white/50 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    {{-- Cover --}}
                    <div class="h-32 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 relative">
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
                            <div class="flex-shrink-0 -mt-8">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                {{ $group->category }}
                            </span>
                        </div>

                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('groups.show', $group) }}" class="flex-1 text-center px-4 py-2 bg-slate-100 text-slate-700 rounded-lg font-medium hover:bg-slate-200 transition-colors">
                                Voir
                            </a>
                            <form method="POST" action="{{ route('groups.join', $group) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-all duration-200">
                                    Rejoindre
                                </button>
                            </form>
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
                    <h3 class="text-lg font-medium text-slate-900">Aucune suggestion pour le moment</h3>
                    <p class="mt-2 text-slate-500">Explorez tous les groupes disponibles !</p>
                    <a href="{{ route('groups.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors">
                        Explorer les groupes
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
