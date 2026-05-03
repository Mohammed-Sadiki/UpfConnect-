<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Événements Universitaires</h2>
        @if(in_array(auth()->user()->role, ['admin', 'teacher']))
        <button onclick="document.getElementById('event-modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full transition shadow-md">
            Créer un événement
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($events as $event)
        <div class="glass-card rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="h-40 bg-gray-200 dark:bg-neutral-800 relative">
                @if($event->image)
                    <img src="{{ Str::startsWith($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                <div class="absolute top-3 right-3 bg-white dark:bg-neutral-900 rounded-lg px-3 py-1 shadow text-center">
                    <span class="block text-xs font-bold text-red-500 uppercase">{{ $event->event_date->translatedFormat('M') }}</span>
                    <span class="block text-lg font-bold text-gray-900 dark:text-white leading-none">{{ $event->event_date->format('d') }}</span>
                </div>
            </div>
            <div class="p-5">
                <a href="{{ route('events.show', $event) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-blue-600">{{ $event->title }}</a>
                <p class="text-sm text-gray-500 mt-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $event->location }}
                </p>
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 dark:border-neutral-800 pt-4">
                    <div class="flex -space-x-2 overflow-hidden">
                        @foreach($event->registrations->take(3) as $reg)
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-neutral-800" src="{{ $reg->user->avatar ? asset('storage/' . $reg->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reg->user->name) }}">
                        @endforeach
                        @if($event->registrations->count() > 3)
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full ring-2 ring-white bg-gray-200 text-[10px] font-medium text-gray-500">+{{ $event->registrations->count() - 3 }}</span>
                        @endif
                    </div>
                    
                    @if($event->registrations->where('user_id', auth()->id())->count() > 0)
                        <span class="text-xs font-semibold text-green-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Inscrit</span>
                    @else
                        <a href="{{ route('events.show', $event) }}" class="text-xs font-semibold text-blue-600 border border-blue-600 px-3 py-1 rounded-full hover:bg-blue-50 transition">Détails</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $events->links() }}
    </div>

    @if(in_array(auth()->user()->role, ['admin', 'teacher']))
    <!-- Event Modal -->
    <div id="event-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-neutral-900 rounded-xl w-full max-w-lg overflow-hidden shadow-2xl">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-neutral-800 flex justify-between items-center">
                <h3 class="text-lg font-semibold dark:text-white">Créer un événement</h3>
                <button onclick="document.getElementById('event-modal').classList.add('hidden')" class="text-gray-500 hover:bg-gray-100 rounded-full p-1 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre</label>
                    <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date et heure</label>
                    <input type="datetime-local" name="event_date" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lieu</label>
                    <input type="text" name="location" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image (Optionnel)</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300">
                </div>
                <div class="pt-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">Publier l'événement</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-app-layout>
