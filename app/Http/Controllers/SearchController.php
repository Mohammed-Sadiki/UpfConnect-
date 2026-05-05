<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Event;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return view('search.results', [
                'query' => $query,
                'users' => collect(),
                'posts' => collect(),
                'events' => collect(),
            ])->with('error', 'Veuillez entrer au moins 2 caractères pour la recherche.');
        }

        $searchTerm = '%' . $query . '%';

        // Recherche d'utilisateurs
        $users = User::where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', $searchTerm)
              ->orWhere('email', 'like', $searchTerm)
              ->orWhere('department', 'like', $searchTerm)
              ->orWhere('bio', 'like', $searchTerm);
        })
        ->where('is_active', true)
        ->where('id', '!=', auth()->id())
        ->limit(20)
        ->get();

        // Recherche de posts publics
        $posts = Post::where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', $searchTerm)
              ->orWhere('content', 'like', $searchTerm);
        })
        ->where(function ($q) {
            $q->where('visibility', 'public')
              ->orWhere('user_id', auth()->id());
        })
        ->with('user')
        ->latest()
        ->limit(10)
        ->get();

        // Recherche d'événements
        $events = Event::where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', $searchTerm)
              ->orWhere('description', 'like', $searchTerm)
              ->orWhere('location', 'like', $searchTerm);
        })
        ->where('event_date', '>=', now()->subDays(30))
        ->orderBy('event_date')
        ->limit(10)
        ->get();

        return view('search.results', compact('query', 'users', 'posts', 'events'));
    }
}
