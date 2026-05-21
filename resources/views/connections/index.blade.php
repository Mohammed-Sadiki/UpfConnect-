<x-app-layout>
    <div class="bg-white p-6 shadow-md border border-gray-200">
        <h2 class="mb-6 text-2xl font-bold tracking-tight text-gray-900">Réseau & connexions</h2>

        @if($pendingRequests->count() > 0)
        <div class="mb-8">
            <h3 class="mb-4 text-lg font-bold text-gray-900">Invitations en attente ({{ $pendingRequests->count() }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pendingRequests as $req)
                <div class="flex items-center justify-between p-4 upf-card">
                    <div class="flex items-center space-x-3">
                        <img class="h-12 w-12 rounded-full" src="{{ $req->sender->avatar ? asset('storage/' . $req->sender->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($req->sender->name) }}">
                        <div>
                            <a href="{{ route('profile.show', $req->sender) }}" class="font-semibold text-gray-900 hover:text-blue-600">{{ $req->sender->name }}</a>
                            <p class="text-xs font-medium text-gray-600">{{ $req->sender->department }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('connections.accept', $req) }}" method="POST">
                            @csrf
                            <button class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></button>
                        </form>
                        <form action="{{ route('connections.reject', $req) }}" method="POST">
                            @csrf
                            <button class="bg-red-100 hover:bg-red-200 text-red-600 p-2 rounded-full"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <h3 class="mb-4 text-lg font-bold text-gray-900">Mes relations ({{ $connections->count() }})</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
            @foreach($connections as $conn)
                @php $friend = $conn->sender_id === auth()->id() ? $conn->receiver : $conn->sender; @endphp
                <div class="p-4 text-center upf-card">
                    <img class="mx-auto mb-3 h-16 w-16 rounded-full" src="{{ $friend->avatar ? asset('storage/'.$friend->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($friend->name).'&background=e2e8f0&color=0f172a' }}" alt="">
                    <a href="{{ route('profile.show', $friend) }}" class="block font-semibold text-gray-900 hover:text-blue-600">{{ $friend->name }}</a>
                    <p class="mb-3 text-sm text-gray-600">{{ $friend->department }}</p>
                    <a href="{{ route('messages.show', $friend) }}" class="inline-block rounded-full border border-blue-600 px-4 py-1.5 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">Message</a>
                </div>
            @endforeach
        </div>

        <h3 class="mb-4 text-lg font-bold text-gray-900">Suggestions pour vous</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($suggestions as $suggest)
            <div class="p-4 text-center upf-card">
                <img class="mx-auto mb-3 h-16 w-16 rounded-full" src="{{ $suggest->avatar ? asset('storage/'.$suggest->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($suggest->name).'&background=e2e8f0&color=0f172a' }}" alt="">
                <a href="{{ route('profile.show', $suggest) }}" class="block font-semibold text-gray-900 hover:text-blue-600">{{ $suggest->name }}</a>
                <p class="mb-3 text-sm text-gray-600">{{ $suggest->department }}</p>
                <form action="{{ route('connections.request', $suggest) }}" method="POST">
                    @csrf
                    <button class="w-full text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-full transition flex justify-center items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg> Se connecter
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>>
