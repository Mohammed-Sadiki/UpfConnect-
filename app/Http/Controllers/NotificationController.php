<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Toutes les notifications marquées comme lues.');
    }

    public function markAndRedirect(Notification $notification)
    {
        // Sécurité : seul le propriétaire peut accéder
        if ($notification->user_id !== auth()->id()) abort(403);

        // Marquer comme lue
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        // Redirection selon le type
        $url = match($notification->type) {
            'new_like', 'new_comment' => isset($notification->data['post_id'])
                ? route('posts.show', $notification->data['post_id'])
                : route('dashboard'),
            'connection_request', 'connection_accepted' => route('connections.index'),
            'group_post' => isset($notification->data['group_id'])
                ? route('groups.show', $notification->data['group_id'])
                : route('groups.index'),
            default => route('notifications.index'),
        };

        return redirect($url);
    }
}
