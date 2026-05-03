<x-app-layout>
    <div class="flex h-[calc(100vh-120px)] bg-white dark:bg-neutral-900 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-neutral-800">
        <!-- Conversations Sidebar -->
        <div class="w-full flex flex-col">
            <div class="p-4 border-b border-gray-200 dark:border-neutral-800">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Messagerie</h2>
            </div>
            <div class="flex-1 overflow-y-auto">
                @forelse($messages as $msg)
                @php $other = $msg->sender_id === auth()->id() ? $msg->receiver : $msg->sender; @endphp
                <a href="{{ route('messages.show', $other) }}" class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-neutral-800 border-b border-gray-100 dark:border-neutral-800 transition">
                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $other->avatar ? asset('storage/' . $other->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($other->name) }}" alt="">
                    <div class="ml-3 flex-1 overflow-hidden">
                        <div class="flex justify-between items-baseline">
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $other->name }}</h3>
                            <span class="text-xs text-gray-500">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate {{ !$msg->read_at && $msg->receiver_id === auth()->id() ? 'font-bold text-gray-900 dark:text-white' : '' }}">
                            {{ $msg->body }}
                        </p>
                    </div>
                </a>
                @empty
                <div class="flex flex-col items-center justify-center py-20 text-gray-500">
                    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="font-medium">Aucun message pour le moment</p>
                    <p class="text-sm mt-1">Visitez un profil et cliquez sur "Message" pour commencer.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
