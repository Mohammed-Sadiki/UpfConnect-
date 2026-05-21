<x-app-layout>
    <div class="mx-auto max-w-lg px-4 pb-12 pt-2 sm:px-6">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900" style="color: #0f172a;">Nouvel événement</h1>
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">Retour à la liste</a>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-md">
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="mb-1 block text-sm font-semibold text-slate-800">Titre</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required
                        class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('title') border-red-500 @enderror">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="event_date" class="mb-1 block text-sm font-semibold text-slate-800">Date et heure</label>
                    <input id="event_date" type="datetime-local" name="event_date" value="{{ old('event_date') }}" required
                        class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('event_date') border-red-500 @enderror">
                    @error('event_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="location" class="mb-1 block text-sm font-semibold text-slate-800">Lieu</label>
                    <input id="location" type="text" name="location" value="{{ old('location') }}" required
                        class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('location') border-red-500 @enderror">
                    @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="description" class="mb-1 block text-sm font-semibold text-slate-800">Description</label>
                    <textarea id="description" name="description" rows="4" required
                        class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="image" class="mb-1 block text-sm font-semibold text-slate-800">Image (optionnel)</label>
                    <input id="image" type="file" name="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-slate-600 file:mr-4 file:rounded-full file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700 @error('image') border border-red-500 rounded-xl p-1 @enderror">
                    @error('image')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-xl bg-blue-600 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-blue-700">Publier</button>
                    <a href="{{ route('events.index') }}" class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-300 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
