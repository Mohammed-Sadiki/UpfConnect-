<x-app-layout>
    <!-- Post Creation Card -->
    <div class="glass-card rounded-xl p-4 shadow-sm mb-6">
        <div class="flex space-x-3">
            <img class="h-12 w-12 rounded-full object-cover flex-shrink-0"
                 src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3b82f6&color=fff' }}"
                 alt="Avatar">
            <button onclick="document.getElementById('post-modal').classList.remove('hidden')"
                    class="flex-1 text-left bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition rounded-full px-5 py-3 text-sm text-slate-500 dark:text-slate-400 font-medium">
                Commencer un post...
            </button>
        </div>
        <div class="flex justify-around mt-3 pt-2 border-t border-gray-100">
            <button onclick="document.getElementById('post-modal').classList.remove('hidden')"
                    class="flex items-center space-x-2 text-gray-500 hover:bg-gray-100 px-4 py-2 rounded-lg transition text-sm font-semibold">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                <span>Média</span>
            </button>
            <a href="{{ route('events.index') }}"
               class="flex items-center space-x-2 text-gray-500 hover:bg-gray-100 px-4 py-2 rounded-lg transition text-sm font-semibold">
                <svg class="w-5 h-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                <span>Événement</span>
            </a>
        </div>
    </div>

    <!-- Feed -->
    <div class="space-y-6">
        @forelse($posts as $post)
        <div class="glass-card rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
            <div class="p-4">
                <!-- Post Header -->
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('profile.show', $post->user) }}">
                            <img class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-100"
                                 src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=3b82f6&color=fff' }}"
                                 alt="">
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $post->user) }}"
                               class="font-semibold text-gray-900 hover:text-blue-600 transition">{{ $post->user->name }}</a>
                            <p class="text-xs text-gray-500">{{ $post->user->bio ?? ($post->user->department ?? 'Membre UPFConnect') }}</p>
                            <div class="flex items-center space-x-1 text-xs text-gray-400 mt-0.5">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                @if($post->visibility === 'public')
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path></svg>
                                @else
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" clip-rule="evenodd"></path></svg>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-800 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white dark:bg-slate-800 rounded-md shadow-lg ring-1 ring-black/10 dark:ring-white/10 z-10">
                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 dark:hover:bg-slate-700">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Post Content -->
                @if($post->title)
                <h3 class="mt-3 font-semibold text-gray-900">{{ $post->title }}</h3>
                @endif
                @if($post->content)
                <div class="mt-2 text-sm text-gray-800 leading-relaxed">
                    <p>{!! nl2br(e($post->content)) !!}</p>
                </div>
                @endif
            </div>

            @if($post->image)
            <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}"
                 class="w-full max-h-80 object-cover" alt="Post Image">
            @endif

            <!-- Stats -->
            <div class="px-4 py-2 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-1 text-xs text-gray-500">
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-blue-500 text-white text-[10px]">👍</span>
                    <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $post->comments->count() }} commentaire{{ $post->comments->count() > 1 ? 's' : '' }}</span>
            </div>

            <!-- Actions -->
            <div class="px-2 py-1 border-t border-gray-100 flex justify-between">
                @php $isLiked = $post->likedByUsers->contains('id', auth()->id()); @endphp
                <button id="like-btn-{{ $post->id }}" onclick="likePost({{ $post->id }})"
                        class="flex-1 flex items-center justify-center space-x-2 rounded-lg py-2 transition font-medium text-sm {{ $isLiked ? 'text-blue-500 bg-blue-50' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-500' }}">
                    <svg id="like-icon-{{ $post->id }}" class="w-5 h-5" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                    <span>J'aime</span>
                </button>
                <button onclick="document.getElementById('comments-{{ $post->id }}').classList.toggle('hidden')"
                        class="flex-1 flex items-center justify-center space-x-2 text-gray-500 hover:bg-gray-50 rounded-lg py-2 transition font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <span>Commenter</span>
                </button>
            </div>

            <!-- Comments Section -->
            <div id="comments-{{ $post->id }}" class="hidden border-t border-gray-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                <div class="px-4 pt-3 pb-2">
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="flex space-x-2">
                        @csrf
                        <img class="h-8 w-8 rounded-full object-cover flex-shrink-0"
                             src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3b82f6&color=fff' }}"
                             alt="">
                        <input type="text" name="content" required placeholder="Ajouter un commentaire..."
                               class="flex-1 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-slate-800 dark:text-slate-200 rounded-full px-4 text-sm focus:outline-none focus:border-blue-500">
                        <button type="submit" class="text-blue-600 font-semibold text-sm px-2 hover:text-blue-800 transition">Publier</button>
                    </form>
                </div>
                <div class="px-4 pb-4 space-y-3">
                    @foreach($post->comments->where('parent_id', null)->take(3) as $comment)
                    <div class="flex space-x-2">
                        <img class="h-8 w-8 rounded-full object-cover flex-shrink-0 mt-1"
                             src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=3b82f6&color=fff' }}"
                             alt="">
                        <div class="bg-white dark:bg-slate-700 border border-gray-100 dark:border-slate-600 px-3 py-2 rounded-2xl flex-1">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('profile.show', $comment->user) }}"
                                   class="font-semibold text-xs text-gray-900 dark:text-slate-200 hover:text-blue-600">{{ $comment->user->name }}</a>
                                <span class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm mt-0.5 text-gray-800 dark:text-slate-300">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="glass-card rounded-xl p-10 text-center text-gray-500 shadow-sm">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <p class="font-medium text-lg">Votre fil est vide</p>
            <p class="text-sm mt-1">Connectez-vous à des personnes ou créez votre premier post !</p>
        </div>
        @endforelse

        <div class="mt-4">{{ $posts->links() }}</div>
    </div>

    <!-- Suggestions de profil -->
    @if(isset($profileSuggestions) && $profileSuggestions->count() > 0)
    <div class="glass-card rounded-xl p-6 shadow-sm mt-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                <i class="fas fa-users text-green-500 mr-2"></i>
                Suggestions de profil
            </h3>
            <a href="{{ route('suggestions.profiles') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Voir tout →
            </a>
        </div>
        
        <div class="space-y-3">
            @foreach($profileSuggestions->take(3) as $suggestion)
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-neutral-800 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-700 transition">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        @if($suggestion['user']->avatar)
                            <img src="{{ Storage::url($suggestion['user']->avatar) }}" 
                                 alt="{{ $suggestion['user']->name }}" 
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <span class="text-white text-sm font-bold">
                                    {{ substr($suggestion['user']->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Badge de connexions en commun -->
                        <div class="absolute -bottom-1 -right-1 bg-green-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                            {{ $suggestion['common_connections_count'] }}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">
                            {{ $suggestion['user']->name }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $suggestion['common_connections_count'] }} 
                            {{ $suggestion['common_connections_count'] > 1 ? 'connexions en commun' : 'connexion en commun' }}
                        </p>
                        @if($suggestion['user']->department)
                            <p class="text-xs text-gray-400">{{ $suggestion['user']->department }}</p>
                        @endif
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <a href="{{ route('profile.show', $suggestion['user']->id) }}" 
                       class="text-gray-600 hover:text-blue-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    
                    <form method="POST" action="{{ route('connections.request', $suggestion['user']->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-600 hover:text-blue-800 transition" title="Se connecter">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Post Creation Modal -->
    <div id="post-modal" class="hidden fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm overflow-y-auto">
        <div class="flex justify-center pt-10 pb-4 min-h-full">
            <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden h-fit">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Créer un post</h3>
                <button onclick="document.getElementById('post-modal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full p-1.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf
                <div class="flex items-center space-x-3 mb-4">
                    <img class="h-12 w-12 rounded-full object-cover"
                         src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3b82f6&color=fff' }}"
                         alt="">
                    <div>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">{{ auth()->user()->name }}</span>
                        <div class="mt-1">
                            <select name="visibility" class="rounded-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-1 text-xs font-medium text-slate-800 dark:text-slate-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <option value="public">🌍 Tout le monde</option>
                                <option value="university">🎓 Mon université</option>
                                <option value="private">🔒 Privé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="text" name="title" placeholder="Titre (optionnel)"
                       class="mb-2 w-full border-0 border-b border-slate-200 bg-transparent pb-2 text-base font-medium text-slate-900 placeholder:text-slate-500 focus:border-blue-400 focus:ring-0">
                <textarea name="content" rows="4" placeholder="De quoi souhaitez-vous parler ? (optionnel si vous ajoutez une image)"
                          class="w-full resize-none border-0 bg-transparent text-sm leading-relaxed text-slate-900 placeholder:text-slate-500 focus:ring-0"></textarea>
                <div id="image-preview" class="hidden mt-3 rounded-xl overflow-hidden border border-gray-200 relative">
                    <img id="preview-img" src="" class="max-h-60 w-full object-cover">
                    <button type="button" onclick="removeImage()" class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition" title="Supprimer l'image">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="border-t border-gray-100 pt-4 mt-2 flex justify-between items-center">
                    <label class="cursor-pointer flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition text-sm font-medium">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                        <span>Ajouter une image</span>
                        <input type="file" name="image" id="post-image-input" class="hidden" accept="image/*"
                               onchange="previewImage(this)">
                    </label>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-full transition shadow-sm">
                        Publier
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
        let selectedImageFile = null;

        function previewImage(input) {
            if (input.files && input.files[0]) {
                selectedImageFile = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            selectedImageFile = null;
            document.getElementById('preview-img').src = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('post-image-input').value = '';
        }

        // Form validation
        document.querySelector('form[action="{{ route('posts.store') }}"]').addEventListener('submit', function(e) {
            const content = this.querySelector('textarea[name="content"]').value.trim();
            const imageInput = this.querySelector('input[name="image"]');
            
            if (!content && !imageInput.files[0]) {
                e.preventDefault();
                alert('Veuillez ajouter du texte ou une image au post.');
                return false;
            }
        });

        function likePost(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`likes-count-${postId}`).innerText = data.likes_count;
                    const btn = document.getElementById(`like-btn-${postId}`);
                    const icon = document.getElementById(`like-icon-${postId}`);
                    if (data.liked) {
                        btn.className = "flex-1 flex items-center justify-center space-x-2 rounded-lg py-2 transition font-medium text-sm text-blue-500 bg-blue-50";
                        icon.setAttribute('fill', 'currentColor');
                    } else {
                        btn.className = "flex-1 flex items-center justify-center space-x-2 rounded-lg py-2 transition font-medium text-sm text-slate-500 hover:bg-blue-50 hover:text-blue-500";
                        icon.setAttribute('fill', 'none');
                    }
                }
            });
        }
    </script>
</x-app-layout>
