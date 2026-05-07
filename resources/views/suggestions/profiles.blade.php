@extends('layouts.app')

@section('title', 'Suggestions de profil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="glass-card p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Suggestions de profil</h1>
                <p class="text-gray-300">
                    Découvrez des personnes avec qui vous avez des connexions en commun
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="glass-button px-4 py-2">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    @if($suggestions->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($suggestions as $suggestion)
                <div class="glass-card p-6 hover:scale-105 transition-all duration-300">
                    <!-- En-tête du profil -->
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="relative">
                            @if($suggestion['user']->avatar)
                                <img src="{{ Storage::url($suggestion['user']->avatar) }}" 
                                     alt="{{ $suggestion['user']->name }}" 
                                     class="w-16 h-16 rounded-full border-2 border-white/20">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                    <span class="text-white text-xl font-bold">
                                        {{ substr($suggestion['user']->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Badge de connexions en commun -->
                            <div class="absolute -bottom-1 -right-1 bg-green-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                                {{ $suggestion['common_connections_count'] }}
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="text-white font-semibold text-lg">
                                {{ $suggestion['user']->name }}
                            </h3>
                            <p class="text-gray-400 text-sm">
                                {{ $suggestion['user']->role === 'student' ? 'Étudiant' : ($suggestion['user']->role === 'teacher' ? 'Enseignant' : 'Admin') }}
                            </p>
                            <p class="text-gray-400 text-sm">
                                {{ $suggestion['user']->department }}
                            </p>
                            @if($suggestion['user']->study_year)
                                <p class="text-gray-400 text-sm">
                                    {{ $suggestion['user']->study_year }}ème année
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Bio -->
                    @if($suggestion['user']->bio)
                        <p class="text-gray-300 text-sm mb-4 line-clamp-2">
                            {{ $suggestion['user']->bio }}
                        </p>
                    @endif

                    <!-- Connexions en commun -->
                    <div class="mb-4">
                        <div class="flex items-center text-green-400 text-sm mb-2">
                            <i class="fas fa-users mr-2"></i>
                            <span class="font-semibold">
                                {{ $suggestion['common_connections_count'] }} 
                                {{ $suggestion['common_connections_count'] > 1 ? 'connexions en commun' : 'connexion en commun' }}
                            </span>
                        </div>
                        
                        @if($suggestion['common_connections']->count() > 0)
                            <div class="flex -space-x-2">
                                @foreach($suggestion['common_connections'] as $common)
                                    <div class="relative group">
                                        @if($common->avatar)
                                            <img src="{{ Storage::url($common->avatar) }}" 
                                                 alt="{{ $common->name }}" 
                                                 class="w-8 h-8 rounded-full border-2 border-white/20 hover:z-10 transition-all">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center border-2 border-white/20 hover:z-10 transition-all">
                                                <span class="text-white text-xs font-bold">
                                                    {{ substr($common->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Tooltip -->
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                                            {{ $common->name }}
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($suggestion['common_connections_count'] > $suggestion['common_connections']->count())
                                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center border-2 border-white/20">
                                        <span class="text-white text-xs font-bold">
                                            +{{ $suggestion['common_connections_count'] - $suggestion['common_connections']->count() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('profile.show', $suggestion['user']->id) }}" 
                           class="flex-1 glass-button px-3 py-2 text-center text-sm">
                            <i class="fas fa-user mr-1"></i>Voir profil
                        </a>
                        
                        <form method="POST" action="{{ route('connections.request', $suggestion['user']->id) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full glass-button px-3 py-2 text-center text-sm bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600">
                                <i class="fas fa-user-plus mr-1"></i>Connecter
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- État vide -->
        <div class="glass-card p-12 text-center">
            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-500 to-gray-600 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-white text-3xl"></i>
            </div>
            
            <h3 class="text-xl font-semibold text-white mb-3">
                Aucune suggestion pour le moment
            </h3>
            
            <p class="text-gray-400 mb-6 max-w-md mx-auto">
                Les suggestions de profil apparaissent lorsque vous avez des connexions en commun avec d'autres utilisateurs. 
                Commencez par vous connecter avec d'autres personnes !
            </p>
            
            <div class="flex justify-center space-x-4">
                <a href="{{ route('search') }}" class="glass-button px-6 py-3">
                    <i class="fas fa-search mr-2"></i>Rechercher des utilisateurs
                </a>
                <a href="{{ route('connections.index') }}" class="glass-button px-6 py-3">
                    <i class="fas fa-user-friends mr-2"></i>Mes connexions
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
