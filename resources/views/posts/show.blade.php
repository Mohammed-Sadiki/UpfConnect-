<x-app-layout>
<div class="max-w-2xl mx-auto">

    {{-- Retour --}}
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
       class="inline-flex items-center space-x-2 text-sm text-slate-500 hover:text-cyan-600 transition mb-6 group">
        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Retour</span>
    </a>

    {{-- Carte du post --}}
    <div class="rounded-2xl border border-white/50 shadow-lg overflow-hidden mb-6"
         style="background:rgba(255,255,255,0.75); backdrop-filter:blur(16px)">

        {{-- Header auteur --}}
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <a href="{{ route('profile.show', $post->user->id) }}">
                    <img class="w-11 h-11 rounded-full object-cover ring-2 ring-slate-200 hover:ring-cyan-400 transition"
                         src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=0ea5e9&color=fff' }}"
                         alt="{{ $post->user->name }}">
                </a>
                <div>
                    <a href="{{ route('profile.show', $post->user->id) }}"
                       class="font-semibold text-slate-800 hover:text-cyan-600 transition text-sm">
                        {{ $post->user->name }}
                    </a>
                    <p class="text-xs text-slate-400">
                        {{ $post->user->department ?? $post->user->role }}
                        · {{ $post->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            {{-- Badge visibilité --}}
            <span class="text-[10px] font-semibold px-2 py-1 rounded-full
                {{ $post->visibility === 'public' ? 'bg-green-50 text-green-600' :
                   ($post->visibility === 'university' ? 'bg-cyan-50 text-cyan-600' : 'bg-slate-100 text-slate-500') }}">
                {{ $post->visibility === 'public' ? '🌍 Public' : ($post->visibility === 'university' ? '🎓 Université' : '🔒 Privé') }}
            </span>
        </div>

        {{-- Contenu --}}
        <div class="p-5">
            @if($post->title)
                <h2 class="text-lg font-bold text-slate-800 mb-2">{{ $post->title }}</h2>
            @endif
            @if($post->content)
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $post->content }}</p>
            @endif
        </div>

        {{-- Image --}}
        @if($post->image)
        <div class="px-5 pb-3">
            <img src="{{ Str::startsWith($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}"
                 class="w-full rounded-xl object-cover max-h-96" alt="Image du post">
        </div>
        @endif

        {{-- Likes & Commentaires stats --}}
        <div class="flex items-center justify-between px-5 py-3 border-t border-slate-100 text-sm text-slate-500">
            <span>❤️ {{ $post->likes_count }} like{{ $post->likes_count > 1 ? 's' : '' }}</span>
            <span>💬 {{ $post->comments->count() }} commentaire{{ $post->comments->count() > 1 ? 's' : '' }}</span>
        </div>

        {{-- Actions Like --}}
        <div class="px-5 pb-4 border-t border-slate-100 pt-3"
             x-data="{
                liked: {{ $post->likedByUsers->contains(auth()->id()) ? 'true' : 'false' }},
                count: {{ $post->likes_count }},
                loading: false,
                toggle() {
                    if (this.loading) return;
                    this.loading = true;
                    fetch('{{ route('posts.like', $post) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.liked = data.liked;
                        this.count = data.likes_count;
                        this.loading = false;
                    })
                    .catch(() => this.loading = false);
                }
             }">
            <div class="flex items-center space-x-4">
                <button @click="toggle()" :disabled="loading"
                        :class="liked ? 'bg-red-50 text-red-500 border-red-200' : 'bg-slate-50 text-slate-500 border-slate-200 hover:bg-red-50 hover:text-red-500 hover:border-red-200'"
                        class="flex items-center space-x-2 px-4 py-2 rounded-xl text-sm font-semibold border transition">
                    <svg class="w-5 h-5 transition-transform" :class="liked ? 'scale-110' : ''"
                         :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span x-text="liked ? 'J\'aime' : 'J\'aime'">J'aime</span>
                    <span x-text="count" class="font-bold ml-1" x-show="count > 0"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Commentaires --}}
    <div class="rounded-2xl border border-gray-200 shadow-md overflow-hidden mb-6 bg-white">
        <div class="p-5 border-b border-gray-200">
            <h3 class="font-bold text-gray-900">Commentaires ({{ $post->comments->count() }})</h3>
        </div>

        {{-- Formulaire commentaire --}}
        <div class="p-5 border-b border-gray-200">
            <form method="POST" action="{{ route('posts.comment', $post) }}" class="flex items-start space-x-3">
                @csrf
                <img class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-200 flex-shrink-0 mt-1"
                     src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=8b5cf6&color=fff' }}"
                     alt="">
                <div class="flex-1">
                    <textarea name="content" rows="2" required
                              placeholder="Écrire un commentaire..."
                              class="w-full bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-700
                                     placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-cyan-400 transition resize-none"
                              style="--tw-ring-color:rgba(14,165,233,0.3)"></textarea>
                    <button type="submit"
                            class="mt-2 px-4 py-1.5 text-sm font-semibold text-white rounded-xl transition shadow-md"
                            style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                        Publier
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste commentaires --}}
        <div class="divide-y divide-gray-200">
            @forelse($post->comments->where('parent_id', null) as $comment)
            <div class="p-5 space-y-4">
                {{-- Commentaire Parent --}}
                <div class="flex items-start space-x-3">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-200 flex-shrink-0"
                         src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=8b5cf6&color=fff' }}"
                         alt="">
                    <div class="flex-1">
                        <div class="bg-gray-50 rounded-xl px-4 py-2.5">
                            <p class="text-sm font-semibold text-gray-900">{{ $comment->user->name }}</p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $comment->content }}</p>
                        </div>
                        <div class="flex items-center space-x-3 text-xs text-gray-500 mt-1 ml-1">
                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                            <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')"
                                    class="font-semibold text-cyan-600 hover:underline">Répondre</button>
                        </div>

                        {{-- Formulaire de réponse caché --}}
                        <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                            <form method="POST" action="{{ route('posts.comment', $post) }}" class="flex items-start space-x-2">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                <input type="text" name="content" required placeholder="Répondre à ce commentaire..."
                                       class="flex-1 bg-white border border-gray-300 rounded-xl px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:border-cyan-400 transition"
                                       style="--tw-ring-color:rgba(14,165,233,0.3)">
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white rounded-xl bg-cyan-600 hover:bg-cyan-700 transition shadow-sm">
                                    Répondre
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Réponses (Imbriquées) --}}
                @if($comment->replies->count() > 0)
                <div class="ml-12 pl-4 border-l-2 border-gray-200 space-y-4">
                    @foreach($comment->replies as $reply)
                    <div class="flex items-start space-x-3">
                        <img class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200 flex-shrink-0"
                             src="{{ $reply->user->avatar ? asset('storage/'.$reply->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name).'&background=8b5cf6&color=fff' }}"
                             alt="">
                        <div class="flex-1 bg-gray-50 rounded-xl px-3 py-2">
                            <p class="text-xs font-semibold text-gray-900">{{ $reply->user->name }}</p>
                            <p class="text-xs text-gray-600 mt-0.5">{{ $reply->content }}</p>
                            <p class="text-[10px] text-gray-500 mt-1">{{ $reply->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 text-sm">
                Aucun commentaire — soyez le premier !
            </div>
            @endforelse
        </div>
    </div>

</div>
</x-app-layout>
