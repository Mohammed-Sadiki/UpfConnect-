<x-app-layout>
<div class="h-[calc(100vh-140px)] -mt-4 -mx-4">
    <div class="glass-card h-full flex overflow-hidden rounded-2xl">
        <!-- Sidebar avec couleurs -->
        <div class="w-1/3 border-r border-white/10 flex flex-col bg-gradient-to-b from-blue-500/5 to-purple-500/5">
            <div class="p-4 border-b border-white/10 bg-gradient-to-r from-blue-500/20 to-purple-500/20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-white">Messagerie</h2>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto">
                @php
                    // Group messages by conversation partner for sidebar
                    $sidebarConversations = [];
                    foreach($allMessages ?? $messages as $sidebarMsg) {
                        $sidebarOther = $sidebarMsg->sender_id === auth()->id() ? $sidebarMsg->receiver : $sidebarMsg->sender;
                        if (!isset($sidebarConversations[$sidebarOther->id])) {
                            $sidebarConversations[$sidebarOther->id] = [
                                'user' => $sidebarOther,
                                'message' => $sidebarMsg
                            ];
                        }
                    }
                @endphp
                @forelse($sidebarConversations as $conversation)
                @php
                    $sidebarOther = $conversation['user'];
                    $sidebarMsg = $conversation['message'];
                    $isActive = isset($user) && $user->id === $sidebarOther->id;
                    $isUnread = !$sidebarMsg->read_at && $sidebarMsg->receiver_id === auth()->id();
                @endphp
                <a href="{{ route('messages.show', $sidebarOther) }}" 
                   class="flex items-center p-4 border-b border-white/5 transition {{ $isActive ? 'bg-gradient-to-r from-blue-500/30 to-purple-500/30 border-l-4 border-l-blue-400' : 'hover:bg-white/5' }}">
                    <div class="relative">
                        @if($sidebarOther->avatar)
                            <img class="h-12 w-12 rounded-full object-cover ring-2 {{ $isActive ? 'ring-blue-400' : 'ring-white/20' }}" src="{{ asset('storage/' . $sidebarOther->avatar) }}">
                        @else
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center ring-2 ring-white/20">
                                <span class="text-white font-bold">{{ substr($sidebarOther->name, 0, 1) }}</span>
                            </div>
                        @endif
                        @if($isUnread)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-pink-500 rounded-full animate-pulse"></span>
                        @endif
                    </div>
                    <div class="ml-3 flex-1 overflow-hidden">
                        <div class="flex justify-between items-baseline">
                            <h3 class="font-semibold text-sm {{ $isActive || $isUnread ? 'text-white' : 'text-gray-300' }}">{{ $sidebarOther->name }}</h3>
                            <span class="text-xs text-gray-500">{{ $sidebarMsg->created_at->format('H:i') }}</span>
                        </div>
                        <p class="text-xs truncate {{ $isUnread ? 'text-white font-semibold' : 'text-gray-400' }}">
                            @if($sidebarMsg->sender_id === auth()->id())
                                <span class="text-blue-400">Vous:</span>
                            @endif
                            {{ $sidebarMsg->body }}
                        </p>
                    </div>
                </a>
                @empty
                <div class="p-4 text-center text-gray-400 text-sm">Aucune conversation</div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area avec couleurs -->
        <div class="w-2/3 flex flex-col bg-gradient-to-b from-gray-900 to-black">
            @if(isset($user))
            <!-- Header coloré -->
            <div class="p-4 bg-gradient-to-r from-blue-600/20 to-purple-600/20 border-b border-white/10 flex items-center">
                <div class="relative">
                    @if($user->avatar)
                        <img class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-400/50" src="{{ asset('storage/' . $user->avatar) }}">
                    @else
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center ring-2 ring-blue-400/50">
                            <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-gray-900"></span>
                </div>
                <div class="ml-4">
                    <h3 class="font-bold text-white text-lg">{{ $user->name }}</h3>
                    <p class="text-xs text-blue-400">{{ $user->department }} • En ligne</p>
                </div>
                <div class="ml-auto flex space-x-2">
                    <a href="{{ route('profile.show', $user) }}" class="p-2 text-gray-400 hover:text-white transition" title="Voir profil">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Messages List avec couleurs -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 flex flex-col" id="chatBox">
                @php
                    $currentDate = null;
                    $lastMessage = null;
                @endphp

                @foreach($messages as $msg)
                    @php
                        $messageDate = $msg->created_at->format('Y-m-d');
                        $showDate = $messageDate !== $currentDate;
                        $currentDate = $messageDate;
                        $isConsecutive = $lastMessage && $lastMessage->sender_id === $msg->sender_id;
                        $lastMessage = $msg;
                    @endphp

                    {{-- Date separator coloré --}}
                    @if($showDate)
                        <div class="flex justify-center my-4">
                            <span class="text-xs text-gray-300 bg-gradient-to-r from-blue-500/30 to-purple-500/30 px-4 py-1.5 rounded-full border border-white/10">
                                @if($msg->created_at->isToday())
                                    Aujourd'hui
                                @elseif($msg->created_at->isYesterday())
                                    Hier
                                @else
                                    {{ $msg->created_at->format('d/m/Y') }}
                                @endif
                            </span>
                        </div>
                    @endif

                    @if($msg->sender_id === auth()->id())
                        {{-- Message envoyé - dégradé bleu/violet --}}
                        <div class="self-end flex flex-col items-end {{ $isConsecutive ? '-mt-2' : '' }}">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl {{ $isConsecutive ? 'rounded-tr-lg' : 'rounded-tr-none' }} rounded-tl-2xl rounded-bl-2xl p-3.5 max-w-[75%] shadow-lg shadow-blue-500/20">
                                <p class="text-sm">{{ $msg->body }}</p>
                            </div>
                            <span class="text-[10px] text-gray-500 mt-1 mr-1">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    @else
                        {{-- Message reçu - glassmorphism --}}
                        <div class="self-start flex flex-col items-start {{ $isConsecutive ? '-mt-2' : '' }}">
                            @if(!$isConsecutive)
                                <div class="flex items-center mb-1.5 ml-1">
                                    <img class="w-7 h-7 rounded-full mr-2 ring-2 ring-purple-400/50" src="{{ $msg->sender->avatar ? asset('storage/' . $msg->sender->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($msg->sender->name) }}" alt="">
                                    <span class="text-xs text-purple-400 font-medium">{{ $msg->sender->name }}</span>
                                </div>
                            @endif
                            <div class="bg-gradient-to-r from-white/10 to-white/5 text-white rounded-2xl {{ $isConsecutive ? 'rounded-tl-lg' : 'rounded-tl-none' }} rounded-tr-2xl rounded-br-2xl p-3.5 max-w-[75%] backdrop-blur-sm border border-white/10 shadow-lg">
                                <p class="text-sm">{{ $msg->body }}</p>
                            </div>
                            <span class="text-[10px] text-gray-500 mt-1 ml-1">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Input Form coloré -->
            <div class="p-4 bg-gradient-to-r from-gray-800 to-gray-900 border-t border-white/10">
                <form action="{{ route('messages.store', $user) }}" method="POST" class="flex items-center space-x-3">
                    @csrf
                    <div class="flex-1 relative">
                        <input type="text" name="body" required placeholder="Écrire un message..." 
                               class="w-full bg-white/5 border border-white/10 rounded-full px-5 py-3 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 transition">
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-full p-3 transition shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
            
            <script>
                // Scroll to bottom
                const chatBox = document.getElementById('chatBox');
                chatBox.scrollTop = chatBox.scrollHeight;
            </script>
            @else
            <div class="flex-1 flex items-center justify-center flex-col">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <p class="text-white font-semibold text-lg mb-2">Vos messages</p>
                <p class="text-gray-400">Sélectionnez une conversation pour commencer</p>
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
