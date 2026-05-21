@php
    $profile = $user->profile;
    $yearStudy = $profile?->year_of_study;
    $deptLine = collect([$user->department, $user->role === 'student' && $yearStudy ? 'Année '.$yearStudy : ($user->role !== 'student' ? ucfirst($user->role) : null)])->filter()->implode(' • ');
@endphp

<x-app-layout>
    <div class="mx-auto max-w-4xl px-4 pb-12 pt-2 sm:px-6 lg:px-8" style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;">
        {{-- Carte profil principale --}}
        <article class="overflow-hidden bg-white shadow-[0_4px_24px_-4px_rgba(15,23,42,0.08)] border border-gray-200">
            {{-- Bannière --}}
            <div class="relative h-40 bg-[#2563eb] sm:h-48 md:h-52" aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 opacity-95"></div>
            </div>

            <div class="relative px-5 pb-8 pt-0 sm:px-8">
                {{-- Avatar + actions --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div class="-mt-16 flex shrink-0 sm:-mt-[4.5rem]">
                        <img
                            class="h-28 w-28 rounded-full border-4 border-white bg-slate-100 object-cover shadow-lg ring-1 ring-slate-200/80 sm:h-32 sm:w-32 md:h-36 md:w-36"
                            src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e2e8f0&color=0f172a&size=256' }}"
                            alt="Photo de profil de {{ $user->name }}"
                            width="144"
                            height="144"
                        >
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:mb-1 sm:justify-end">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-sky-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:from-sky-700 hover:to-violet-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-violet-400 focus:ring-offset-2">
                                Modifier le profil
                            </a>
                        @else
                            @if(!$isConnected)
                                <form action="{{ route('connections.request', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-full bg-[#2563eb] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Se connecter
                                    </button>
                                </form>
                            @elseif($isConnected->status === 'pending')
                                <span class="rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600">En attente</span>
                            @else
                                <a href="{{ route('messages.show', $user) }}" class="inline-flex items-center justify-center rounded-full border-2 border-[#2563eb] bg-white px-5 py-2 text-sm font-semibold text-[#2563eb] transition hover:bg-blue-50">
                                    Message
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Identité --}}
                <div class="mt-5 sm:mt-6">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        {{ $user->name }}
                    </h1>
                    @if($user->bio)
                        <p class="mt-2 max-w-3xl text-[15px] leading-relaxed text-slate-600">
                            {{ $user->bio }}
                        </p>
                    @endif
                    @if($deptLine !== '')
                        <p class="mt-2 text-sm font-medium text-slate-500">
                            {{ $deptLine }}
                        </p>
                    @endif
                </div>

                @if($profile)
                    @php
                        $skills = $profile->skills ?? [];
                        $interests = $profile->interests ?? [];
                    @endphp
                    @if(count($skills) || count($interests))
                        <div class="mt-8 grid gap-8 border-t border-slate-100 pt-8 md:grid-cols-2">
                            @if(count($skills))
                                <div>
                                    <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500" style="font-family: system-ui, -apple-system, sans-serif;">Compétences</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($skills as $skill)
                                            <span class="inline-flex items-center rounded-full bg-[#1e40af] px-3 py-1.5 text-xs font-semibold text-white shadow-sm">{{ trim($skill) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if(count($interests))
                                <div>
                                    <h2 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500" style="font-family: system-ui, -apple-system, sans-serif;">Centres d&rsquo;intérêt</h2>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($interests as $interest)
                                            <span class="inline-flex items-center rounded-full bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white shadow-sm">{{ trim($interest) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($profile->linkedin_url || $profile->github_url || $profile->cv_path)
                        <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-slate-100 pt-6">
                            @if($profile->linkedin_url)
                                <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-[#0a66c2] transition hover:underline">
                                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    LinkedIn
                                </a>
                            @endif
                            @if($profile->github_url)
                                <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-800 transition hover:text-slate-950 hover:underline">
                                    <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                    GitHub
                                </a>
                            @endif
                            @if($profile->cv_path)
                                <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 transition hover:underline">
                                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Voir le CV
                                </a>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </article>

        {{-- Activité --}}
        <section class="mt-10" aria-labelledby="activity-heading">
            <h2 id="activity-heading" class="mb-5 text-xs font-bold uppercase tracking-[0.2em] text-gray-500" style="font-family: system-ui, -apple-system, sans-serif;">
                Activité
            </h2>
            <div class="space-y-4">
                @forelse($user->posts as $post)
                    <article class="bg-white p-5 shadow-[0_2px_12px_-2px_rgba(15,23,42,0.06)] transition hover:shadow-md sm:p-6 border border-gray-200">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="text-[15px] leading-relaxed text-gray-900">{!! nl2br(e($post->content)) !!}</p>
                                @if($post->image)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($post->image, 'http') ? $post->image : asset('storage/'.$post->image) }}" class="mt-4 max-h-72 w-full rounded-xl object-cover" alt="" loading="lazy">
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-gray-200 pt-3 text-xs text-gray-500">
                            <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->translatedFormat('d M Y') }}</time>
                            <span aria-hidden="true">·</span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            @if(isset($post->comments_count))
                                <span aria-hidden="true">·</span>
                                <span>{{ $post->comments_count }} {{ $post->comments_count > 1 ? 'commentaires' : 'commentaire' }}</span>
                            @endif
                            @if($post->likes_count)
                                <span aria-hidden="true">·</span>
                                <span>{{ $post->likes_count }} j&rsquo;aime</span>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center">
                        <p class="text-sm font-medium text-gray-600">Aucune activité publique pour le moment.</p>
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('dashboard') }}" class="mt-3 inline-block text-sm font-semibold text-[#2563eb] hover:underline">Publier sur le fil</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
