<x-app-layout>
    <div class="glass-card rounded-xl shadow-sm p-6 max-w-4xl mx-auto mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h2>
            @if($notifications->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:underline">Tout marquer comme lu</button>
            </form>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notif)
            <div class="border {{ $notif->read_at ? 'border-gray-200 dark:border-neutral-800 bg-transparent' : 'border-blue-200 dark:border-blue-900 bg-blue-50 dark:bg-blue-900/20' }} rounded-xl p-4 flex items-start space-x-3 transition">
                <div class="flex-shrink-0 mt-1">
                    @if($notif->type === 'new_like')
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    @elseif($notif->type === 'new_comment')
                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
                    @elseif(str_contains($notif->type, 'connection'))
                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                    @else
                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900 dark:text-gray-200 {{ !$notif->read_at ? 'font-semibold' : '' }}">{{ $notif->data['message'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <p>Aucune notification pour le moment.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>
