<x-app-layout>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl" style="color: #0f172a;">Événements universitaires</h2>
        @if(in_array(auth()->user()->role, ['admin', 'teacher']))
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('events.create') }}" class="rounded-full border border-blue-600 bg-white px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm transition hover:bg-blue-50">
                Formulaire complet
            </a>
            <button type="button" onclick="document.getElementById('event-modal').classList.remove('hidden')" class="rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">
                Créer (rapide)
            </button>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($events as $event)
        <div class="glass-card rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="relative h-40 bg-slate-100">
                @if($event->image)
                    <img src="{{ Str::startsWith($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" class="h-full w-full object-cover" alt="">
                @else
                    <div class="flex h-full w-full items-center justify-center text-slate-400">
                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                <div class="absolute right-3 top-3 rounded-lg bg-white px-3 py-1 text-center shadow">
                    <span class="block text-xs font-bold text-red-500 uppercase">{{ $event->event_date->translatedFormat('M') }}</span>
                    <span class="block text-lg font-bold leading-none text-slate-900">{{ $event->event_date->format('d') }}</span>
                </div>
            </div>
            <div class="p-5">
                <a href="{{ route('events.show', $event) }}" class="text-lg font-bold text-slate-900 hover:text-blue-600" style="color: #0f172a;">{{ $event->title }}</a>
                <p class="mt-2 flex items-center text-sm text-slate-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $event->location }}
                </p>
                <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                    <div class="flex -space-x-2 overflow-hidden">
                        @foreach($event->registrations->take(3) as $reg)
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white" src="{{ $reg->user->avatar ? asset('storage/' . $reg->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reg->user->name) }}">
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
        <div class="w-full max-w-lg overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="text-lg font-semibold text-slate-900">Créer un événement</h3>
                <button type="button" onclick="document.getElementById('event-modal').classList.add('hidden')" class="rounded-full p-1 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 p-4">
                @csrf
                @if($errors->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">
                        <ul class="list-inside list-disc space-y-0.5">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">Titre</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">Date et heure</label>
                    <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">Lieu</label>
                    <input type="text" name="location" value="{{ old('location') }}" required class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">Description</label>
                    <textarea name="description" rows="3" required class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-800">Image (optionnel)</label>
                    <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700">
                </div>
                <div class="pt-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">Publier l'événement</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('event-modal')?.classList.remove('hidden');
            });
        </script>
    @endif
</x-app-layout>
