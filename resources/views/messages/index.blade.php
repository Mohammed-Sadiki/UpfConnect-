<x-app-layout>
    <div class="flex h-[calc(100vh-120px)] bg-white dark:bg-neutral-900 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-neutral-800">
        <!-- Conversations Sidebar -->
        <div class="container mx-auto px-4 py-6">
            <!-- Header avec dégradé -->
            <div class="glass-card p-6 mb-6 bg-gradient-to-r from-blue-500/20 via-purple-500/20 to-pink-500/20">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Messagerie</h1>
                            <p class="text-gray-300 text-sm">Vos conversations</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('dashboard') }}" class="glass-button px-4 py-2 text-sm">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Accueil
                        </a>
                    </div>
                </div>
            </div>

            @if($messages->count() > 0)
                <div class="grid gap-4">
                    @foreach($messages as $msg)
                    @php
                        $other = $msg->sender_id === auth()->id() ? $msg->receiver : $msg->sender;
                        $isUnread = !$msg->read_at && $msg->receiver_id === auth()->id();
                    @endphp
                    <a href="{{ route('messages.show', $other) }}" 
                       class="glass-card p-4 hover:scale-[1.02] transition-all duration-300 {{ $isUnread ? 'bg-gradient-to-r from-blue-500/30 to-purple-500/30 border-blue-400/50' : '' }}">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar avec effet néon -->
                            <div class="relative">
                                @if($other->avatar)
                                    <img src="{{ Storage::url($other->avatar) }}" 
                                         alt="{{ $other->name }}"
                                         class="w-14 h-14 rounded-full object-cover ring-2 ring-white/20">
                                @else
                                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center ring-2 ring-white/20">
                                        <span class="text-white text-xl font-bold">{{ substr($other->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Badge en ligne ou notification -->
                                @if($isUnread)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-pink-500 to-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold animate-pulse shadow-lg shadow-pink-500/50">
                                        !
                                    </span>
                                @else
                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-gray-900"></span>
                                @endif
                            </div>
                            
                            <!-- Info conversation -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="font-bold text-white text-lg truncate">{{ $other->name }}</h3>
                                    <span class="text-xs text-gray-400 whitespace-nowrap ml-2">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <p class="text-sm {{ $isUnread ? 'text-white font-semibold' : 'text-gray-400' }} truncate">
                                    @if($msg->sender_id === auth()->id())
                                        <span class="text-blue-400">Vous:</span>
                                    @else
                                        <span class="text-purple-400">{{ $other->name }}:</span>
                                    @endif
                                    {{ $msg->body }}
                                </p>
                                
                                <!-- Métadonnées -->
                                <div class="flex items-center mt-2 space-x-3 text-xs text-gray-500">
                                    @if($other->department)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                            {{ $other->department }}
                                        </span>
                                    @endif
                                    @if($isUnread)
                                        <span class="text-pink-400 font-semibold flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                            </svg>
                                            Non lu
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Flèche -->
                            <div class="text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <!-- État vide coloré -->
                <div class="glass-card p-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Aucune conversation</h3>
                    <p class="text-gray-400 mb-6">Commencez à discuter avec vos connexions</p>
                    <a href="{{ route('connections.index') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                        Voir mes connexions
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
