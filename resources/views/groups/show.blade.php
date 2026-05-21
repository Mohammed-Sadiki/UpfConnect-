<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Header du groupe --}}
        <div class="glass-card overflow-hidden shadow-lg mb-6">
            {{-- Cover --}}
            <div class="h-40 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 relative">
                @if($isAdmin)
                    <div class="absolute top-4 right-4 flex space-x-2">
                        <a href="{{ route('groups.members', $group) }}" class="px-3 py-1.5 bg-white/90 text-slate-700 rounded-lg text-sm font-medium hover:bg-white transition-colors">
                            Gérer membres
                        </a>
                        <a href="{{ route('groups.edit', $group) }}" class="px-3 py-1.5 bg-white/90 text-slate-700 rounded-lg text-sm font-medium hover:bg-white transition-colors">
                            Modifier
                        </a>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="px-6 pb-6">
                <div class="flex flex-col sm:flex-row sm:items-end -mt-12 sm:-mt-16">
                    <div class="flex-shrink-0">
                        @if($group->image)
                            <img src="{{ asset('storage/' . $group->image) }}" alt="{{ $group->name }}" class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl object-cover border-4 border-white shadow-xl">
                        @else
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center border-4 border-white shadow-xl">
                                <span class="text-white text-4xl sm:text-5xl font-bold">{{ substr($group->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-6 flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $group->name }}</h1>
                            @if($group->visibility === 'private')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Privé
                                </span>
                            @endif
                        </div>
                        <p class="mt-2 text-slate-600 max-w-2xl">{{ $group->description }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Créé par {{ $group->creator->name }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                {{ $totalMembers }} membres
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($group->category === 'academic') bg-purple-100 text-purple-800
                                @elseif($group->category === 'club') bg-green-100 text-green-800
                                @elseif($group->category === 'project') bg-amber-100 text-amber-800
                                @elseif($group->category === 'career') bg-blue-100 text-blue-800
                                @else bg-slate-100 text-slate-800
                                @endif">
                                {{ $group->category }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex flex-wrap gap-3">
                    @if($isMember)
                        <form method="POST" action="{{ route('groups.leave', $group) }}" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir quitter ce groupe ?')" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition-colors">
                                Quitter le groupe
                            </button>
                        </form>
                    @elseif($hasPendingRequest)
                        <button disabled class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg font-medium cursor-not-allowed">
                            Demande en attente
                        </button>
                    @else
                        <form method="POST" action="{{ route('groups.join', $group) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-all duration-200 shadow-lg shadow-cyan-500/25">
                                {{ $group->visibility === 'public' ? 'Rejoindre' : 'Demander à rejoindre' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Create Post --}}
                @if($isMember)
                    <div class="glass-card p-6 shadow-sm">
                        <h3 class="text-sm font-semibold text-slate-700 mb-4">Publier dans le groupe</h3>
                        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" x-data="{ imagePreview: null }">
                            @csrf
                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                            <input type="hidden" name="visibility" value="public">

                            <div class="mb-4">
                                <textarea name="content" rows="3" placeholder="Partagez quelque chose avec le groupe..." class="w-full rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500 resize-none" required></textarea>
                            </div>

                            {{-- Image Preview --}}
                            <div x-show="imagePreview" class="mb-4 relative">
                                <img :src="imagePreview" class="max-h-48 rounded-lg">
                                <button type="button" @click="imagePreview = null; $refs.imageInput.value = ''" class="absolute top-2 right-2 w-8 h-8 bg-slate-800 text-white rounded-full flex items-center justify-center hover:bg-slate-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center px-4 py-2 text-slate-600 hover:text-cyan-600 cursor-pointer transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">Ajouter une image</span>
                                    <input type="file" name="image" accept="image/*" class="hidden" x-ref="imageInput" @change="const file = $event.target.files[0]; if(file) imagePreview = URL.createObjectURL(file)">
                                </label>
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg font-medium hover:from-cyan-600 hover:to-blue-700 transition-all duration-200">
                                    Publier
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif(!$isMember && !$hasPendingRequest)
                    <div class="bg-slate-50 rounded-xl p-6 text-center">
                        <p class="text-slate-600">Rejoignez ce groupe pour publier et interagir avec les membres.</p>
                    </div>
                @endif

                {{-- Posts --}}
                @if($posts->count() > 0)
                    <div class="space-y-4">
                        @foreach($posts as $post)
                            @include('components.post-card', ['post' => $post])
                        @endforeach
                    </div>
                    {{ $posts->links() }}
                @else
                    <div class="glass-card p-12 text-center shadow-sm">
                        <div class="w-16 h-16 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">Aucune publication</h3>
                        <p class="mt-2 text-slate-500">Soyez le premier à publier dans ce groupe !</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Membres --}}
                <div class="glass-card p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-700 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Membres ({{ $totalMembers }})
                    </h3>
                    <div class="space-y-3">
                        @foreach($members as $member)
                            <div class="flex items-center space-x-3">
                                <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : asset('images/default-avatar.png') }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover">
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('profile.show', $member) }}" class="text-sm font-medium text-slate-800 hover:text-cyan-600 truncate">{{ $member->name }}</a>
                                    @if($group->isAdmin($member))
                                        <span class="text-xs text-purple-600">Admin</span>
                                    @elseif($group->isModerator($member))
                                        <span class="text-xs text-blue-600">Modo</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($totalMembers > 10)
                        <a href="{{ route('groups.members', $group) }}" class="mt-4 block text-center text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                            Voir tous les membres
                        </a>
                    @endif
                </div>

                {{-- À propos --}}
                <div class="glass-card p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-700 mb-4">À propos</h3>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Visibilité</span>
                            <span class="font-medium">{{ $group->visibility === 'public' ? 'Public' : 'Privé' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Créé le</span>
                            <span class="font-medium">{{ $group->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Catégorie</span>
                            <span class="font-medium capitalize">{{ $group->category }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Publications</span>
                            <span class="font-medium">{{ $group->posts_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
