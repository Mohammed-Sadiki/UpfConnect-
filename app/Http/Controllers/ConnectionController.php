<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Pagination pour les demandes en attente (max 50)
        $pendingRequests = Connection::with('sender')
            ->where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->limit(50)
            ->get();
            
        // Pagination pour les connexions (max 100)
        $connections = Connection::with(['sender', 'receiver'])
            ->where(function($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->where('status', 'accepted')
            ->limit(100)
            ->get();
            
        // Suggestions limitées à 10 maximum avec eager loading
        $suggestions = User::with(['connectionsSent', 'connectionsReceived'])
            ->where('department', $user->department)
            ->where('id', '!=', $user->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('connections.index', compact('pendingRequests', 'connections', 'suggestions'));
    }

    public function sendRequest(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous connecter à vous-même.');
        }

        $connection = Connection::firstOrCreate([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
        ]);
        
        if ($connection->wasRecentlyCreated) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'connection_request',
                'data' => [
                    'message' => auth()->user()->name . ' a envoyé une demande de connexion.'
                ]
            ]);
            return back()->with('success', 'Demande de connexion envoyée.');
        }

        return back()->with('info', 'Demande de connexion déjà envoyée.');
    }

    public function accept(Connection $connection)
    {
        if ($connection->receiver_id !== auth()->id()) {
            abort(403);
        }

        $connection->update(['status' => 'accepted']);
        
        \App\Models\Notification::create([
            'user_id' => $connection->sender_id,
            'type' => 'connection_accepted',
            'data' => [
                'message' => auth()->user()->name . ' a accepté votre demande de connexion.'
            ]
        ]);

        return back()->with('success', 'Connexion acceptée.');
    }

    public function reject(Connection $connection)
    {
        if ($connection->receiver_id !== auth()->id()) {
            abort(403);
        }

        $connection->update(['status' => 'rejected']);
        return back()->with('success', 'Demande refusée.');
    }

    public function destroy(Connection $connection)
    {
        if ($connection->sender_id !== auth()->id() && $connection->receiver_id !== auth()->id()) {
            abort(403);
        }
        
        $connection->delete();
        return back()->with('success', 'Connexion supprimée.');
    }
}
