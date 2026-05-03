<x-app-layout>
    <div class="glass-card rounded-xl shadow-sm overflow-hidden max-w-4xl mx-auto mt-6">
        @if($event->image)
            <img src="{{ Str::startsWith($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" class="w-full h-64 object-cover" alt="Event cover">
        @else
            <div class="w-full h-64 bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif

        <div class="p-8 relative">
            <div class="absolute top-0 right-8 -mt-12 bg-white dark:bg-neutral-900 rounded-xl p-4 shadow-lg text-center border border-gray-100 dark:border-neutral-800">
                <span class="block text-sm font-bold text-red-500 uppercase">{{ $event->event_date->translatedFormat('M') }}</span>
                <span class="block text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $event->event_date->format('d') }}</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $event->title }}</h1>
            <p class="text-sm text-gray-500 flex items-center mb-6">
                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $event->location }} &nbsp;•&nbsp; 
                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $event->event_date->format('H:i') }}
            </p>

            <div class="flex items-center space-x-3 mb-8 pb-8 border-b border-gray-200 dark:border-neutral-800">
                <img class="h-10 w-10 rounded-full" src="{{ $event->creator->avatar ? asset('storage/' . $event->creator->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($event->creator->name) }}" alt="">
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Organisé par <a href="{{ route('profile.show', $event->creator) }}" class="text-blue-600 hover:underline">{{ $event->creator->name }}</a></p>
                    <p class="text-xs text-gray-500">{{ $event->creator->department }}</p>
                </div>
            </div>

            <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                <h3 class="text-xl font-bold mb-4">À propos de cet événement</h3>
                <p>{!! nl2br(e($event->description)) !!}</p>
            </div>

            <div class="mt-10 bg-gray-50 dark:bg-neutral-800/50 rounded-xl p-6 border border-gray-100 dark:border-neutral-800 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1">Participants ({{ $event->registrations->count() }})</h4>
                    <div class="flex -space-x-2 overflow-hidden mt-2">
                        @foreach($event->registrations->take(10) as $reg)
                        <a href="{{ route('profile.show', $reg->user) }}">
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-neutral-800 object-cover" src="{{ $reg->user->avatar ? asset('storage/' . $reg->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reg->user->name) }}" title="{{ $reg->user->name }}">
                        </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    @if($isRegistered)
                        <form action="{{ route('events.unregister', $event) }}" method="POST">
                            @csrf
                            <button class="bg-red-100 hover:bg-red-200 text-red-600 font-semibold py-2 px-6 rounded-full transition flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Se désinscrire
                            </button>
                        </form>
                    @else
                        <form action="{{ route('events.register', $event) }}" method="POST">
                            @csrf
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-full transition shadow-md flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Participer
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
