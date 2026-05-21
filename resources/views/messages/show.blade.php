<x-app-layout>
    <style>[x-cloak]{display:none!important}</style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('messageMenu', (align, msgId) => ({
                open: false,
                menuStyle: '',
                align,
                msgId,
                toggleMenu() {
                    if (!this.open) {
                        window.dispatchEvent(new CustomEvent('message-menu-close-others', { detail: { except: this.msgId } }));
                    }
                    this.open = !this.open;
                    if (this.open) {
                        this.$nextTick(() => requestAnimationFrame(() => this.positionMenu()));
                    }
                },
                positionMenu() {
                    const btn = this.$refs.menuBtn;
                    const menu = this.$refs.menuPanel;
                    if (!btn || !menu) return;
                    const br = btn.getBoundingClientRect();
                    const mw = menu.offsetWidth || 192;
                    const mh = menu.offsetHeight || 280;
                    const pad = 8;
                    const vw = window.innerWidth;
                    const vh = window.innerHeight;
                    let top = br.top - mh - pad;
                    let left = this.align === 'right' ? (br.right - mw) : br.left;
                    if (top < pad) {
                        top = br.bottom + pad;
                    }
                    if (top + mh > vh - pad) {
                        top = Math.max(pad, vh - mh - pad);
                    }
                    if (left + mw > vw - pad) {
                        left = vw - mw - pad;
                    }
                    if (left < pad) {
                        left = pad;
                    }
                    this.menuStyle = 'top:' + Math.round(top) + 'px;left:' + Math.round(left) + 'px';
                },
            }));
        });
    </script>
    <div class="h-[calc(100vh-140px)] -mt-4 -mx-4"
        x-data="{ deleteModal: { show: false, action: '' }, openDelete(action){ this.deleteModal.action=action; this.deleteModal.show=true; } }">

        {{-- ===== MODAL CONFIRMATION SUPPRESSION ===== --}}
        <div x-show="deleteModal.show" x-transition.opacity
            class="fixed inset-0 z-[9999] flex items-center justify-center"
            style="display:none; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px)">
            <div x-show="deleteModal.show" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-80 overflow-hidden">
                {{-- Icône --}}
                <div class="flex justify-center pt-8 pb-4">
                    <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
                {{-- Texte --}}
                <div class="text-center px-6 pb-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Supprimer le message ?</h3>
                    <p class="text-sm text-slate-500">Ce message sera définitivement supprimé. Cette action est
                        irréversible.</p>
                </div>
                {{-- Actions --}}
                <div class="border-t border-slate-100">
                    <form :action="deleteModal.action" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-3.5 text-red-500 font-bold text-sm hover:bg-red-50 transition border-b border-slate-100">
                            Supprimer
                        </button>
                    </form>
                    <button @click="deleteModal.show = false"
                        class="w-full py-3.5 text-slate-600 font-medium text-sm hover:bg-slate-50 transition">
                        Annuler
                    </button>
                </div>
            </div>
        </div>

        <div class="h-full flex overflow-hidden glass-card shadow-lg">

            {{-- ===== SIDEBAR CONVERSATIONS ===== --}}
            <div class="w-1/3 border-r border-slate-200 flex flex-col">
                {{-- Header sidebar --}}
                <div class="p-4 border-b border-slate-200"
                    style="background:linear-gradient(135deg,rgba(14,165,233,0.12),rgba(139,92,246,0.12))">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shadow"
                            style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-800">Messagerie</h2>
                    </div>
                </div>

                {{-- Liste conversations --}}
                <div class="flex-1 overflow-y-auto">
                    @php
                        $sidebarConversations = [];
                        foreach ($allMessages ?? $messages as $sidebarMsg) {
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
                        <a href="{{ route('messages.show', $sidebarOther) }}" class="flex items-center p-4 border-b border-slate-100 transition-all duration-200
                              {{ $isActive ? 'border-l-4 border-l-cyan-500' : 'hover:bg-slate-50' }}"
                            style="{{ $isActive ? 'background:rgba(14,165,233,0.08)' : '' }}">
                            <div class="relative flex-shrink-0">
                                @if($sidebarOther->avatar)
                                    <img class="h-11 w-11 rounded-full object-cover ring-2 {{ $isActive ? 'ring-cyan-400' : 'ring-slate-200' }}"
                                        src="{{ asset('storage/' . $sidebarOther->avatar) }}" alt="">
                                @else
                                    <div class="h-11 w-11 rounded-full flex items-center justify-center ring-2 {{ $isActive ? 'ring-cyan-400' : 'ring-slate-200' }}"
                                        style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                                        <span
                                            class="text-white font-bold text-sm">{{ substr($sidebarOther->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                @if($isUnread)
                                    <span
                                        class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                                @endif
                                <span
                                    class="absolute -bottom-0.5 -right-0.5 w-3 h-3 {{ $sidebarOther->isOnline() ? 'bg-emerald-500' : 'bg-slate-300' }} rounded-full border-2 border-white" title="{{ $sidebarOther->isOnline() ? 'En ligne' : 'Hors ligne' }}"></span>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <div class="flex justify-between items-baseline">
                                    <h3 class="font-semibold text-sm text-slate-800 truncate">{{ $sidebarOther->name }}</h3>
                                    <span
                                        class="text-[10px] text-slate-400 whitespace-nowrap ml-2">{{ $sidebarMsg->created_at->format('H:i') }}</span>
                                </div>
                                <p
                                    class="text-xs truncate mt-0.5 {{ $isUnread ? 'text-slate-700 font-semibold' : 'text-slate-500' }}">
                                    @if($sidebarMsg->sender_id === auth()->id())
                                        <span class="text-cyan-600">Vous :</span>
                                    @endif
                                    {{ $sidebarMsg->body }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-sm">Aucune conversation</div>
                    @endforelse
                </div>
            </div>

            {{-- ===== ZONE CHAT ===== --}}
            <div class="w-2/3 flex flex-col" style="background:rgba(248,250,252,0.9)">
                @if(isset($user))

                    {{-- Header chat --}}
                    <div class="p-4 border-b border-slate-200 flex items-center" style="background:rgba(255,255,255,0.8)">
                        <div class="relative flex-shrink-0">
                            @if($user->avatar)
                                <img class="h-11 w-11 rounded-full object-cover ring-2 ring-cyan-300"
                                    src="{{ asset('storage/' . $user->avatar) }}" alt="">
                            @else
                                <div class="h-11 w-11 rounded-full flex items-center justify-center ring-2 ring-cyan-300"
                                    style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                                    <span class="text-white font-bold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span
                                class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 {{ $user->isOnline() ? 'bg-emerald-500' : 'bg-slate-300' }} rounded-full border-2 border-white"></span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-slate-800 text-base">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-500">
                                @if($user->department){{ $user->department }} • @endif
                                <span class="{{ $user->isOnline() ? 'text-emerald-600 font-semibold' : 'text-slate-400' }}">
                                    {{ $user->isOnline() ? 'En ligne' : 'Hors ligne' }}
                                </span>
                            </p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('profile.show', $user) }}"
                                class="p-2 text-slate-400 hover:text-cyan-600 transition rounded-lg hover:bg-cyan-50"
                                title="Voir profil">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Messages --}}
                    <div class="flex-1 overflow-y-auto p-5 space-y-3 flex flex-col" id="chatBox"
                        @scroll.passive="window.dispatchEvent(new CustomEvent('chat-scroll-reposition'))">
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

                            {{-- Séparateur de date --}}
                            @if($showDate)
                                <div class="flex justify-center my-3">
                                    <span
                                        class="text-xs text-slate-500 bg-white px-4 py-1 rounded-full border border-slate-200 shadow-sm">
                                        @if($msg->created_at->isToday()) Aujourd'hui
                                        @elseif($msg->created_at->isYesterday()) Hier
                                        @else {{ $msg->created_at->format('d/m/Y') }}
                                        @endif
                                    </span>
                                </div>
                            @endif

                            @if($msg->sender_id === auth()->id())
                                {{-- Message envoyé --}}
                                <div class="self-end flex flex-col items-end {{ $isConsecutive ? '-mt-1' : '' }} max-w-[65%]"
                                    x-data="messageMenu('right', {{ $msg->id }})"
                                    @message-menu-close-others.window="if ($event.detail.except !== msgId) open = false"
                                    @chat-scroll-reposition.window="open && positionMenu()"
                                    @resize.window="open && positionMenu()">
                                    <div class="flex items-end space-x-1.5 group w-full justify-end">
                                        {{-- Bouton menu --}}
                                        <div class="relative flex-shrink-0">
                                            <button type="button" x-ref="menuBtn" @click.stop="toggleMenu()"
                                                class="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 mb-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition @click.away="open = false" x-cloak
                                                x-ref="menuPanel"
                                                :style="menuStyle"
                                                class="fixed z-[10050] min-w-[13rem] rounded-xl border border-slate-100 bg-white py-1.5 font-sans antialiased text-[15px] leading-snug shadow-xl">
                                                {{-- Copier --}}
                                                <button
                                                    onclick="navigator.clipboard.writeText({{ json_encode($msg->body) }}).then(()=>{ this.innerHTML='<svg class=\'w-4 h-4 text-green-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg><span class=\'text-green-600\'>Copié !</span>'; const r=this.closest('[x-data]'); setTimeout(()=>{ if(r&&window.Alpine) Alpine.$data(r).open=false },800) })"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-slate-800 transition hover:bg-slate-100">
                                                    <svg class="w-5 h-5 shrink-0 text-slate-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>Copier</span>
                                                </button>
                                                {{-- Transférer : pré-remplit le champ --}}
                                                <button
                                                    onclick="document.querySelector('input[name=body]').value = {{ json_encode('Transfert : ' . $msg->body) }}; document.querySelector('input[name=body]').focus(); const r=this.closest('[x-data]'); if(r&&window.Alpine) Alpine.$data(r).open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-slate-800 transition hover:bg-slate-100">
                                                    <svg class="w-5 h-5 shrink-0 text-slate-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                    </svg>
                                                    <span>Transférer</span>
                                                </button>
                                                {{-- Épingler --}}
                                                <button
                                                    onclick="alert('Épinglé !'); const r=this.closest('[x-data]'); if(r&&window.Alpine) Alpine.$data(r).open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-slate-800 transition hover:bg-slate-100">
                                                    <svg class="w-5 h-5 shrink-0 text-slate-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                    </svg>
                                                    <span>Épingler</span>
                                                </button>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                {{-- Supprimer --}}
                                                <button @click="openDelete('{{ route('messages.destroy', $msg) }}'); open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-red-700 transition hover:bg-red-100">
                                                    <svg class="w-5 h-5 shrink-0 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Supprimer</span>
                                                </button>
                                                {{-- Signaler --}}
                                                <button
                                                    onclick="alert('Message signalé. Merci !'); const r=this.closest('[x-data]'); if(r&&window.Alpine) Alpine.$data(r).open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-orange-800 transition hover:bg-orange-100">
                                                    <svg class="w-5 h-5 shrink-0 text-orange-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <span>Signaler</span>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Bulle message --}}
                                        <div class="text-white px-4 py-2.5 shadow-sm break-words"
                                            style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6); border-radius:18px 4px 18px 18px;">
                                            <p class="text-sm font-medium leading-relaxed">{{ $msg->body }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-1 mr-1">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                            @else
                                {{-- Message reçu --}}
                                <div class="self-start flex flex-col items-start {{ $isConsecutive ? '-mt-1' : '' }} max-w-[65%]"
                                    x-data="messageMenu('left', {{ $msg->id }})"
                                    @message-menu-close-others.window="if ($event.detail.except !== msgId) open = false"
                                    @chat-scroll-reposition.window="open && positionMenu()"
                                    @resize.window="open && positionMenu()">
                                    @if(!$isConsecutive)
                                        <div class="flex items-center mb-1.5 ml-1">
                                            <img class="w-6 h-6 rounded-full mr-2 ring-1 ring-slate-200"
                                                src="{{ $msg->sender->avatar ? asset('storage/' . $msg->sender->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($msg->sender->name) . '&background=8b5cf6&color=fff' }}"
                                                alt="">
                                            <span class="text-xs text-purple-600 font-semibold">{{ $msg->sender->name }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-end space-x-1.5 group w-full">
                                        {{-- Bulle message --}}
                                        <div class="bg-white text-slate-700 px-4 py-2.5 border border-slate-200 shadow-sm break-words flex-1"
                                            style="border-radius: 4px 18px 18px 18px;">
                                            <p class="text-sm font-medium text-slate-800 leading-relaxed">{{ $msg->body }}</p>
                                        </div>
                                        {{-- Bouton menu --}}
                                        <div class="relative flex-shrink-0">
                                            <button type="button" x-ref="menuBtn" @click.stop="toggleMenu()"
                                                class="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 mb-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition @click.away="open = false" x-cloak
                                                x-ref="menuPanel"
                                                :style="menuStyle"
                                                class="fixed z-[10050] min-w-[13rem] rounded-xl border border-slate-100 bg-white py-1.5 font-sans antialiased text-[15px] leading-snug shadow-xl">
                                                {{-- Copier --}}
                                                <button
                                                    onclick="navigator.clipboard.writeText({{ json_encode($msg->body) }}).then(()=>{ this.innerHTML='<svg class=\'w-4 h-4 text-green-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M5 13l4 4L19 7\'/></svg><span class=\'text-green-600\'>Copié !</span>'; const r=this.closest('[x-data]'); setTimeout(()=>{ if(r&&window.Alpine) Alpine.$data(r).open=false },800) })"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-slate-800 transition hover:bg-slate-100">
                                                    <svg class="w-5 h-5 shrink-0 text-slate-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>Copier</span>
                                                </button>
                                                {{-- Transférer --}}
                                                <button
                                                    onclick="document.querySelector('input[name=body]').value = {{ json_encode('Transfert : ' . $msg->body) }}; document.querySelector('input[name=body]').focus(); const r=this.closest('[x-data]'); if(r&&window.Alpine) Alpine.$data(r).open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-slate-800 transition hover:bg-slate-100">
                                                    <svg class="w-5 h-5 shrink-0 text-slate-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                    </svg>
                                                    <span>Transférer</span>
                                                </button>
                                                {{-- Épingler --}}
                                                <button
                                                    onclick="alert('Épinglé !'); const r=this.closest('[x-data]'); if(r&&window.Alpine) Alpine.$data(r).open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-slate-800 transition hover:bg-slate-100">
                                                    <svg class="w-5 h-5 shrink-0 text-slate-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                                    </svg>
                                                    <span>Épingler</span>
                                                </button>
                                                <div class="border-t border-slate-100 my-1"></div>
                                                {{-- Signaler --}}
                                                <button
                                                    onclick="alert('Message signalé. Merci !'); const r=this.closest('[x-data]'); if(r&&window.Alpine) Alpine.$data(r).open=false"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-[15px] font-semibold text-orange-800 transition hover:bg-orange-100">
                                                    <svg class="w-5 h-5 shrink-0 text-orange-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    <span>Signaler</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] text-slate-400 mt-1 ml-1">{{ $msg->created_at->format('H:i') }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Formulaire envoi --}}
                    <div class="p-4 border-t border-slate-200 bg-white">
                        <form action="{{ route('messages.store', $user) }}" method="POST"
                            class="flex items-center space-x-3">
                            @csrf
                            <input type="text" name="body" required placeholder="Écrire un message..." class="flex-1 bg-slate-50 border border-slate-200 rounded-full px-5 py-2.5 text-sm text-slate-700 placeholder-slate-400
                                      focus:outline-none focus:ring-2 focus:border-cyan-400 transition"
                                style="--tw-ring-color:rgba(14,165,233,0.3)">
                            <button type="submit"
                                class="text-white rounded-full p-3 transition shadow-md hover:shadow-lg transform hover:scale-105"
                                style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <script>
                        const chatBox = document.getElementById('chatBox');
                        chatBox.scrollTop = chatBox.scrollHeight;
                    </script>

                @else
                    {{-- Aucune conversation sélectionnée --}}
                    <div class="flex-1 flex items-center justify-center flex-col">
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-5 shadow-lg"
                            style="background:linear-gradient(135deg,#0ea5e9,#8b5cf6)">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <p class="text-slate-700 font-semibold text-lg mb-1">Vos messages</p>
                        <p class="text-slate-400 text-sm">Sélectionnez une conversation pour commencer</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>