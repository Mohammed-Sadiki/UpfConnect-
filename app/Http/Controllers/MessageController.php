<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Find latest messages for each conversation
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->get()
            ->unique(function ($item) use ($user) {
                return $item->sender_id == $user->id ? $item->receiver_id : $item->sender_id;
            });
            
        return view('messages.index', compact('messages'));
    }

    public function show(User $user)
    {
        $authId = auth()->id();
        
        // Mark as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })->orderBy('created_at', 'asc')->get();

        return view('messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'body' => $request->body,
        ]);

        return back();
    }
}
