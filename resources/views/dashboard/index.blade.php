<x-app-layout>
    <!-- Post Creation Card -->
    <div class="glass-card rounded-xl p-4 shadow-sm mb-6">
        <div class="flex space-x-3">
            <img class="h-12 w-12 rounded-full object-cover flex-shrink-0"
                 src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3b82f6&color=fff' }}"
                 alt="Avatar">
            <button onclick="document.getElementById('post-modal').classList.remove('hidden')"
                    class="flex-1 text-left bg-gray-100 dark:bg-neutral-800 hover:bg-gray-200 dark:hover:bg-neutral-700 transition rounded-full px-5 py-3 text-sm text-gray-500 dark:text-gray-400 font-medium">
                Commencer un post...
            </button>
        </div>
        <div class="flex justify-around mt-3 pt-2 border-t border-gray-100 dark:border-neutral-800">
            <button onclick="document.getElementById('post-modal').classList.remove('hidden')"
                    class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800 px-4 py-2 rounded-lg transition text-sm font-semibold">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                <span>Média</span>
            </button>
            <a href="{{ route('events.index') }}"
               class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-800 px-4 py-2 rounded-lg transition text-sm font-semibold">
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
                            <img class="h-12 w-12 rounded-full object-cover ring-2 ring-gray-100 dark:ring-neutral-800"
                                 src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($post->user->name).'&background=3b82f6&color=fff' }}"
                                 alt="">
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $post->user) }}"
                               class="font-semibold text-gray-900 dark:text-white hover:text-blue-600 transition">{{ $post->user->name }}</a>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $post->user->bio ?? ($post->user->department ?? 'Membre UniConnect') }}</p>
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
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white dark:bg-neutral-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-10">
                            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 dark:hover:bg-neutral-700">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Post Content -->
                @if($post->title)
                <h3 class="mt-3 font-semibold text-gray-900 dark:text-white">{{ $post->title }}</h3>
                @endif
                <div class="mt-2 text-sm text-gray-800 dark:text-gray-200 leading-relaxed">
                    <p>{!! nl2br(e($post->content)) !!}</p>
                </div>
            </div>

            @if($post->image)
            <img src="{{ str_starts_with($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}"
                 class="w-full max-h-80 object-cover" alt="Post Image">
            @endif

            <!-- Stats -->
            <div class="px-4 py-2 border-t border-gray-100 dark:border-neutral-800 flex items-center justify-between">
                <div class="flex items-center space-x-1 text-xs text-gray-500">
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-blue-500 text-white text-[10px]">👍</span>
                    <span id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $post->comments->count() }} commentaire{{ $post->comments->count() > 1 ? 's' : '' }}</span>
            </div>

            <!-- Actions -->
            <div class="px-2 py-1 border-t border-gray-100 dark:border-neutral-800 flex justify-between">
                <button onclick="likePost({{ $post->id }})"
                        class="flex-1 flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 rounded-lg py-2 transition font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                    <span>J'aime</span>
                </button>
                <button onclick="document.getElementById('comments-{{ $post->id }}').classList.toggle('hidden')"
                        class="flex-1 flex items-center justify-center space-x-2 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-neutral-800 rounded-lg py-2 transition font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <span>Commenter</span>
                </button>
            </div>

            <!-- Comments Section -->
            <div id="comments-{{ $post->id }}" class="hidden border-t border-gray-100 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-800/50">
                <div class="px-4 pt-3 pb-2">
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="flex space-x-2">
                        @csrf
                        <img class="h-8 w-8 rounded-full object-cover flex-shrink-0"
                             src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=3b82f6&color=fff' }}"
                             alt="">
                        <input type="text" name="content" required placeholder="Ajouter un commentaire..."
                               class="flex-1 bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700 rounded-full px-4 text-sm focus:outline-none focus:border-blue-500 dark:text-white">
                        <button type="submit" class="text-blue-600 font-semibold text-sm px-2 hover:text-blue-800 transition">Publier</button>
                    </form>
                </div>
                <div class="px-4 pb-4 space-y-3">
                    @foreach($post->comments->take(3) as $comment)
                    <div class="flex space-x-2">
                        <img class="h-8 w-8 rounded-full object-cover flex-shrink-0 mt-1"
                             src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=3b82f6&color=fff' }}"
                             alt="">
                        <div class="bg-white dark:bg-neutral-900 border border-gray-100 dark:border-neutral-700 px-3 py-2 rounded-2xl flex-1">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('profile.show', $comment->user) }}"
                                   class="font-semibold text-xs dark:text-white hover:text-blue-600">{{ $comment->user->name }}</a>
                                <span class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm mt-0.5 text-gray-800 dark:text-gray-200">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="glass-card rounded-xl p-10 text-center text-gray-500 shadow-sm">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <p class="font-medium text-lg">Votre fil est vide</p>
            <p class="text-sm mt-1">Connectez-vous à des personnes ou créez votre premier post !</p>
        </div>
        @endforelse

        <div class="mt-4">{{ $posts->links() }}</div>
    </div>

    <!-- Post Creation Modal -->
    <div id="post-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white dark:bg-neutral-900 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-neutral-800 flex justify-between items-center">
                <h3 class="text-lg font-bold dark:text-white">Créer un post</h3>
                <button onclick="document.getElementById('post-modal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-neutral-800 rounded-full p-1.5 transition">
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
                        <span class="font-semibold dark:text-white">{{ auth()->user()->name }}</span>
                        <div class="mt-1">
                            <select name="visibility" class="text-xs border border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white rounded-full px-3 py-1 focus:outline-none focus:border-blue-500">
                                <option value="public">🌍 Tout le monde</option>
                                <option value="university">🎓 Mon université</option>
                                <option value="private">🔒 Privé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="text" name="title" placeholder="Titre (optionnel)"
                       class="w-full border-0 border-b border-gray-100 dark:border-neutral-800 focus:ring-0 focus:border-blue-300 text-base font-medium dark:bg-neutral-900 dark:text-white mb-2 pb-2">
                <textarea name="content" rows="5" required placeholder="De quoi souhaitez-vous parler ?"
                          class="w-full border-0 focus:ring-0 resize-none text-sm dark:bg-neutral-900 dark:text-white leading-relaxed"></textarea>
                <div id="image-preview" class="hidden mt-2 rounded-xl overflow-hidden">
                    <img id="preview-img" src="" class="max-h-48 w-full object-cover">
                </div>
                <div class="border-t border-gray-100 dark:border-neutral-800 pt-4 mt-2 flex justify-between items-center">
                    <label class="cursor-pointer flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition text-sm font-medium">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                        <span>Ajouter une image</span>
                        <input type="file" name="image" class="hidden" accept="image/*"
                               onchange="if(this.files[0]){document.getElementById('preview-img').src=URL.createObjectURL(this.files[0]);document.getElementById('image-preview').classList.remove('hidden');}">
                    </label>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-full transition shadow-sm">
                        Publier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
                }
            });
        }
    </script>
</x-app-layout>
