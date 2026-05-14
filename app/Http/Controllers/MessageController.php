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
        $userId = $user->id;

        // Get all messages where user is sender or receiver, ordered by latest first
        $allMessages = Message::where(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group by conversation partner and keep only the latest message for each
        $conversations = [];
        foreach ($allMessages as $message) {
            // Determine the other person in the conversation
            $otherId = ($message->sender_id === $userId) ? $message->receiver_id : $message->sender_id;

            // Only add if we haven't seen this conversation partner yet
            // (since we ordered by created_at desc, first one is the latest)
            if (!isset($conversations[$otherId])) {
                $conversations[$otherId] = $message;
            }
        }

        // Convert to collection and sort by latest message date
        $messages = collect($conversations)->sortByDesc('created_at')->values();

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

        // Get conversation messages for current chat
        $messages = Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Get all messages for sidebar conversation list
        $allMessages = Message::where(function($q) use ($authId) {
                $q->where('sender_id', $authId)
                  ->orWhere('receiver_id', $authId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('messages.show', compact('messages', 'user', 'allMessages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $user->id,
            'body'        => $request->body,
        ]);

        return back()->with('success', 'Message envoyé.');
    }

    public function destroy(Message $message)
    {
        // Only the sender can delete their own message
        if ($message->sender_id !== auth()->id()) {
            abort(403);
        }

        $receiverId = $message->receiver_id;
        $message->delete();

        $other = User::find($receiverId);
        return redirect()->route('messages.show', $other)
                         ->with('success', 'Message supprimé.');
    }
}
