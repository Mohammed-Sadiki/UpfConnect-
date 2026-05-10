<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex items-center mb-6">
            <a href="{{ route('groups.show', $group) }}" class="mr-4 p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Gérer les membres</h1>
                <p class="text-slate-500">{{ $group->name }}</p>
            </div>
        </div>

        {{-- Demandes en attente --}}
        @if($pendingRequests->count() > 0)
            <div class="bg-white/80 backdrop-blur-xl rounded-xl p-6 border border-white/50 shadow-sm mb-6">
                <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                    <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                    Demandes en attente ({{ $pendingRequests->count() }})
                </h2>
                <div class="space-y-4">
                    @foreach($pendingRequests as $request)
                        <div class="flex items-center justify-between p-4 bg-amber-50 rounded-lg border border-amber-200">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $request->user->avatar ? asset('storage/' . $request->user->avatar) : asset('images/default-avatar.png') }}" alt="{{ $request->user->name }}" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <a href="{{ route('profile.show', $request->user) }}" class="font-medium text-slate-800 hover:text-cyan-600">{{ $request->user->name }}</a>
                                    <p class="text-sm text-slate-500">Demande envoyée {{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('groups.members.approve', [$group, $request]) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors">
                                        Approuver
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('groups.members.reject', [$group, $request]) }}" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Rejeter cette demande ?')" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg font-medium hover:bg-slate-300 transition-colors">
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Tous les membres --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-xl p-6 border border-white/50 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Membres ({{ $members->total() }})</h2>

            <div class="space-y-4">
                @forelse($members as $membership)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $membership->user->avatar ? asset('storage/' . $membership->user->avatar) : asset('images/default-avatar.png') }}" alt="{{ $membership->user->name }}" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('profile.show', $membership->user) }}" class="font-medium text-slate-800 hover:text-cyan-600">{{ $membership->user->name }}</a>
                                    @if($membership->role === 'admin')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Admin
                                        </span>
                                    @elseif($membership->role === 'moderator')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Modérateur
                                        </span>
                                    @endif
                                    @if($group->created_by === $membership->user_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            Créateur
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500">Membre depuis {{ $membership->joined_at ? $membership->joined_at->format('d M Y') : 'N/A' }}</p>
                            </div>
                        </div>

                        @can('admin', $group)
                            @if($membership->user_id !== auth()->id())
                                <div class="flex items-center space-x-2">
                                    {{-- Changer le rôle --}}
                                    <form method="POST" action="{{ route('groups.members.role', [$group, $membership]) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="text-sm rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                                            <option value="member" {{ $membership->role === 'member' ? 'selected' : '' }}>Membre</option>
                                            <option value="moderator" {{ $membership->role === 'moderator' ? 'selected' : '' }}>Modérateur</option>
                                            <option value="admin" {{ $membership->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>

                                    {{-- Retirer --}}
                                    <form method="POST" action="{{ route('groups.members.remove', [$group, $membership]) }}" class="inline" onsubmit="return confirm('Retirer ce membre du groupe ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Retirer du groupe">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endcan
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-slate-500">Aucun membre trouvé.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($members->hasPages())
                <div class="mt-6">
                    {{ $members->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
