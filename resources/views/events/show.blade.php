<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 pb-12 pt-2 sm:px-6 lg:px-8" style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;">
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_8px_30px_-8px_rgba(15,23,42,0.12)]">
            {{-- Bannière + badge date --}}
            <div class="relative h-56 sm:h-64 md:h-72">
                @if($event->image)
                    <img src="{{ Str::startsWith($event->image, 'http') ? $event->image : asset('storage/'.$event->image) }}"
                        class="h-full w-full object-cover"
                        alt="">
                @else
                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-200 via-slate-100 to-slate-200">
                        <svg class="h-20 w-20 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <div class="absolute bottom-4 right-4 rounded-xl border border-slate-200/80 bg-white px-4 py-3 text-center shadow-lg sm:right-6 sm:bottom-5">
                    <span class="block text-xs font-bold uppercase tracking-wide text-red-500">{{ $event->event_date->translatedFormat('M') }}</span>
                    <span class="mt-0.5 block text-3xl font-bold leading-none text-slate-900">{{ $event->event_date->format('d') }}</span>
                </div>
            </div>

            <div class="px-5 pb-8 pt-8 sm:px-10 sm:pt-10">
                <h1 class="text-2xl font-bold leading-tight tracking-tight text-slate-900 sm:text-3xl" style="color: #0f172a;">
                    {{ $event->title }}
                </h1>

                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-medium text-slate-700">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $event->location }}
                    </span>
                    <span class="hidden text-slate-300 sm:inline" aria-hidden="true">|</span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $event->event_date->format('d/m/Y') }} à {{ $event->event_date->format('H:i') }}
                    </span>
                </div>

                @if(!empty($canEdit) && $canEdit)
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet événement ? Les inscriptions seront annulées.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                @endif

                <div class="mt-8 flex items-center gap-3 border-b border-slate-200 pb-8">
                    <img class="h-12 w-12 shrink-0 rounded-full border-2 border-slate-100 object-cover ring-1 ring-slate-200"
                        src="{{ $event->creator->avatar ? asset('storage/'.$event->creator->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($event->creator->name).'&background=e2e8f0&color=0f172a&size=128' }}"
                        alt="">
                    <div>
                        <p class="text-sm text-slate-800">
                            Organisé par
                            <a href="{{ route('profile.show', $event->creator) }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">{{ $event->creator->name }}</a>
                        </p>
                        <p class="mt-0.5 text-xs font-medium text-slate-600">{{ $event->creator->department }}</p>
                    </div>
                </div>

                <section class="mt-8" aria-labelledby="event-desc-heading">
                    <h2 id="event-desc-heading" class="text-lg font-bold text-slate-900 sm:text-xl" style="color: #0f172a;">
                        À propos de cet événement
                    </h2>
                    <div class="mt-4 max-w-none text-base leading-relaxed text-slate-700">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </section>

                <div class="mt-10 flex flex-col gap-6 rounded-xl bg-slate-800 px-5 py-5 text-slate-50 shadow-inner sm:flex-row sm:items-center sm:justify-between sm:px-6 sm:py-5">
                    <div>
                        <p class="text-base font-bold text-slate-50" style="color: #f8fafc;">Participants ({{ $event->registrations->count() }})</p>
                        <div class="mt-3 flex -space-x-2 overflow-hidden">
                            @foreach($event->registrations->take(10) as $reg)
                                <a href="{{ route('profile.show', $reg->user) }}" class="relative z-0 inline-block hover:z-10" title="{{ $reg->user->name }}">
                                    <img class="h-9 w-9 rounded-full border-2 border-slate-800 object-cover ring-2 ring-slate-600"
                                        src="{{ $reg->user->avatar ? asset('storage/'.$reg->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($reg->user->name).'&background=94a3b8&color=fff' }}"
                                        alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="shrink-0">
                        @if($isRegistered)
                            <form action="{{ route('events.unregister', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-400/80 bg-red-500/10 px-6 py-3 text-sm font-semibold text-red-200 transition hover:bg-red-500/20 sm:w-auto">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Se désinscrire
                                </button>
                            </form>
                        @else
                            <form action="{{ route('events.register', $event) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-900/30 transition hover:bg-blue-500 sm:w-auto">
                                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Participer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </article>
    </div>
</x-app-layout>
