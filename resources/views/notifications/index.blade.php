<x-app-layout>
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Notifications</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                @if(($unreadNotificationsCount ?? 0) > 0)
                    {{ $unreadNotificationsCount }} non lue{{ $unreadNotificationsCount > 1 ? 's' : '' }}
                @else
                    Tout est à jour ✓
                @endif
            </p>
        </div>
        @if($notifications->count() > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit"
                    class="text-sm font-semibold text-cyan-600 hover:text-cyan-700 bg-cyan-50 hover:bg-cyan-100 px-4 py-2 rounded-xl transition">
                Tout marquer comme lu
            </button>
        </form>
        @endif
    </div>

    {{-- Liste --}}
    <div class="space-y-2">
        @forelse($notifications as $notif)
        @php
            $isUnread = is_null($notif->read_at);
        @endphp

        <a href="{{ route('notifications.read', $notif->id) }}"
           class="flex items-start p-4 rounded-2xl border transition group
                  {{ $isUnread
                        ? 'bg-cyan-50/60 dark:bg-cyan-900/20 border-cyan-200 dark:border-cyan-800 hover:bg-cyan-50 dark:hover:bg-cyan-900/30'
                        : 'glass-card border-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">

            {{-- Icône type --}}
            <div class="flex-shrink-0 w-11 h-11 rounded-full flex items-center justify-center mr-4 shadow-sm
                        {{ $isUnread ? 'bg-white dark:bg-slate-800 shadow-cyan-100' : 'bg-slate-100 dark:bg-slate-700' }}">
                @if($notif->type === 'new_like')
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                @elseif($notif->type === 'new_comment')
                    <svg class="w-5 h-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" clip-rule="evenodd"/>
                    </svg>
                @elseif(str_contains($notif->type, 'connection'))
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                    </svg>
                @elseif(str_contains($notif->type, 'group'))
                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a4 4 0 00-3.5-3.97A5.5 5.5 0 0118 18h-2zM4 18H2a5.5 5.5 0 015.5-4.97A4 4 0 003.5 17H4v1z"/>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>

            {{-- Texte --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-800 leading-snug {{ $isUnread ? 'font-semibold' : '' }}">
                    {{ $notif->data['message'] ?? 'Nouvelle notification' }}
                </p>
                @if(isset($notif->data['post_id']))
                    <p class="text-xs text-slate-400 mt-0.5 truncate">
                        Voir le post concerné →
                    </p>
                @elseif(str_contains($notif->type, 'connection'))
                    <p class="text-xs text-slate-400 mt-0.5">Voir mes connexions →</p>
                @endif
                <p class="text-xs text-cyan-600 font-medium mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>

            {{-- Point non-lu + chevron --}}
            <div class="flex-shrink-0 ml-3 flex flex-col items-center space-y-2 mt-1">
                @if($isUnread)
                <div class="w-2.5 h-2.5 rounded-full bg-cyan-500"></div>
                @endif
                <svg class="w-4 h-4 text-slate-300 group-hover:text-cyan-400 transition mt-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        @empty
        <div class="flex flex-col items-center py-20 text-center">
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-slate-600 font-semibold">Aucune notification</p>
            <p class="text-sm text-slate-400 mt-1">Vous êtes à jour !</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif

</div>
</x-app-layout>
