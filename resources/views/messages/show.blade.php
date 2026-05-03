<x-app-layout>
    <div class="flex h-[calc(100vh-120px)] bg-white dark:bg-neutral-900 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-neutral-800">
        <!-- Sidebar -->
        <div class="w-1/3 border-r border-gray-200 dark:border-neutral-800 flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Messagerie</h2>
            </div>
            <div class="flex-1 overflow-y-auto">
                @forelse($messages as $msg)
                @php $other = $msg->sender_id === auth()->id() ? $msg->receiver : $msg->sender; @endphp
                <a href="{{ route('messages.show', $other) }}" class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-neutral-800 border-b border-gray-100 dark:border-neutral-800 transition">
                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $other->avatar ? asset('storage/' . $other->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($other->name) }}">
                    <div class="ml-3 flex-1 overflow-hidden">
                        <div class="flex justify-between items-baseline">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $other->name }}</h3>
                            <span class="text-xs text-gray-500">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate {{ !$msg->read_at && $msg->receiver_id === auth()->id() ? 'font-bold text-gray-900 dark:text-white' : '' }}">{{ $msg->body }}</p>
                    </div>
                </a>
                @empty
                <div class="p-4 text-center text-gray-500 text-sm">Aucun message.</div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="w-2/3 flex flex-col bg-gray-50 dark:bg-neutral-900">
            @if(isset($user))
            <!-- Header -->
            <div class="p-4 bg-white dark:bg-neutral-800 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <img class="h-10 w-10 rounded-full" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}">
                <div class="ml-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $user->department }}</p>
                </div>
            </div>

            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 flex flex-col" id="chatBox">
                @foreach($messages as $msg)
                    @if($msg->sender_id === auth()->id())
                        <div class="self-end bg-blue-600 text-white rounded-t-xl rounded-l-xl p-3 max-w-[70%]">
                            <p class="text-sm">{{ $msg->body }}</p>
                            <span class="text-[10px] text-blue-200 mt-1 block text-right">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    @else
                        <div class="self-start bg-white dark:bg-neutral-800 text-gray-800 dark:text-gray-200 rounded-t-xl rounded-r-xl p-3 max-w-[70%] shadow-sm border border-gray-100 dark:border-neutral-700">
                            <p class="text-sm">{{ $msg->body }}</p>
                            <span class="text-[10px] text-gray-500 mt-1 block">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Input Form -->
            <div class="p-4 bg-white dark:bg-neutral-800 border-t border-gray-200 dark:border-neutral-700">
                <form action="{{ route('messages.store', $user) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <input type="text" name="body" required placeholder="Écrire un message..." class="flex-1 bg-gray-100 dark:bg-neutral-900 border-none rounded-full px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 dark:text-white">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2.5 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
            
            <script>
                // Scroll to bottom
                const chatBox = document.getElementById('chatBox');
                chatBox.scrollTop = chatBox.scrollHeight;
            </script>
            @else
            <div class="flex-1 flex items-center justify-center text-gray-500 flex-col">
                <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <p>Sélectionnez une conversation pour commencer</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
