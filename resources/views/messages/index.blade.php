<x-app-layout>
    <div class="max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-lg"
                     style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Messagerie</h1>
                    <p class="text-xs text-slate-500">Vos conversations</p>
                </div>
            </div>
        </div>

        @if($messages->count() > 0)
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm overflow-hidden">
                @foreach($messages as $msg)
                @php
                    $other    = $msg->sender_id === auth()->id() ? $msg->receiver : $msg->sender;
                    $isUnread = !$msg->read_at && $msg->receiver_id === auth()->id();
                @endphp
                <a href="{{ route('messages.show', $other) }}"
                   class="flex items-center p-4 border-b border-slate-100 last:border-0 transition-all duration-200 hover:bg-slate-50
                          {{ $isUnread ? 'border-l-4 border-l-cyan-500' : '' }}"
                   style="{{ $isUnread ? 'background:rgba(14,165,233,0.05)' : '' }}">

                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        @if($other->avatar)
                            <img src="{{ Storage::url($other->avatar) }}"
                                 alt="{{ $other->name }}"
                                 class="w-13 h-13 w-12 h-12 rounded-full object-cover ring-2 ring-slate-200">
                        @else
                            <div class="w-12 h-12 rounded-full flex items-center justify-center ring-2 ring-slate-200"
                                 style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                                <span class="text-white text-lg font-bold">{{ substr($other->name, 0, 1) }}</span>
                            </div>
                        @endif

                        @if($isUnread)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white flex items-center justify-center">
                                <span class="text-white text-[8px] font-bold">!</span>
                            </span>
                        @else
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                        @endif
                    </div>

                    {{-- Infos --}}
                    <div class="ml-4 flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h3 class="font-semibold text-slate-800 {{ $isUnread ? 'font-bold' : '' }} truncate">{{ $other->name }}</h3>
                            <span class="text-xs text-slate-400 whitespace-nowrap ml-2">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm truncate {{ $isUnread ? 'text-slate-700 font-medium' : 'text-slate-500' }}">
                            @if($msg->sender_id === auth()->id())
                                <span class="text-cyan-600 font-medium">Vous :</span>
                            @else
                                <span class="text-purple-600 font-medium">{{ $other->name }} :</span>
                            @endif
                            {{ $msg->body }}
                        </p>
                        @if($other->department)
                            <p class="text-xs text-slate-400 mt-0.5">{{ $other->department }}</p>
                        @endif
                    </div>

                    {{-- Flèche --}}
                    <svg class="w-4 h-4 text-slate-300 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endforeach
            </div>
        @else
            {{-- État vide --}}
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-sm p-14 text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center shadow-lg"
                     style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Aucune conversation</h3>
                <p class="text-slate-500 text-sm mb-6">Commencez à discuter avec vos connexions</p>
                <a href="{{ route('connections.index') }}"
                   class="inline-flex items-center px-5 py-2.5 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition"
                   style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                    Voir mes connexions
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
