<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Créer un groupe</h1>
            <p class="text-slate-500 mt-1">Créez une communauté autour de vos intérêts</p>
        </div>

        <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-8 border border-white/50 shadow-lg">
            <form method="POST" action="{{ route('groups.store') }}" enctype="multipart/form-data" x-data="{ imagePreview: null }">
                @csrf

                {{-- Image --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Image du groupe</label>
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            <div x-show="!imagePreview" class="w-24 h-24 rounded-xl bg-slate-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <img x-show="imagePreview" :src="imagePreview" class="w-24 h-24 rounded-xl object-cover">
                        </div>
                        <div>
                            <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-lg font-medium hover:bg-slate-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Choisir une image
                                <input type="file" name="image" accept="image/*" class="hidden" @change="const file = $event.target.files[0]; if(file) imagePreview = URL.createObjectURL(file)">
                            </label>
                            <p class="mt-2 text-xs text-slate-500">JPG, PNG, GIF. Max 5MB.</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nom --}}
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nom du groupe *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Ex: Club de programmation">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500" placeholder="Décrivez votre groupe, ses objectifs et ses activités...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    {{-- Catégorie --}}
                    <div>
                        <label for="category" class="block text-sm font-medium text-slate-700 mb-2">Catégorie *</label>
                        <select name="category" id="category" required class="w-full rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Visibilité --}}
                    <div>
                        <label for="visibility" class="block text-sm font-medium text-slate-700 mb-2">Visibilité *</label>
                        <select name="visibility" id="visibility" required class="w-full rounded-lg border-slate-300 focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public - Tout le monde peut voir et rejoindre</option>
                            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Privé - Sur invitation seulement</option>
                        </select>
                        @error('visibility')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                    <a href="{{ route('groups.index') }}" class="text-slate-600 hover:text-slate-800 font-medium">
                        Annuler
                    </a>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl font-semibold hover:from-cyan-600 hover:to-blue-700 transition-all duration-200 shadow-lg shadow-cyan-500/25">
                        Créer le groupe
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
