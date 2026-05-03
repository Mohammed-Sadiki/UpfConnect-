<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display the personalized feed for the authenticated user.
     */
    public function feed()
    {
        $user = auth()->user();

        // Get IDs of accepted connections
        $connectionIds = $user->connectionsSent()
            ->where('status', 'accepted')->pluck('receiver_id')
            ->merge(
                $user->connectionsReceived()
                    ->where('status', 'accepted')->pluck('sender_id')
            );

        $posts = Post::with(['user', 'comments.user'])
            ->where(function ($query) use ($user, $connectionIds) {
                $query->whereIn('user_id', $connectionIds)
                      ->orWhere('user_id', $user->id)
                      ->orWhere('visibility', 'public');
            })
            ->latest()
            ->paginate(10);

        // Upcoming events for right sidebar
        $upcomingEvents = \App\Models\Event::where('event_date', '>=', now())
            ->orderBy('event_date')->take(3)->get();

        // Suggestions: users in same department not yet connected
        $suggestions = \App\Models\User::where('department', $user->department)
            ->where('id', '!=', $user->id)
            ->whereNotIn('id', $connectionIds)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('posts', 'user', 'upcomingEvents', 'suggestions'));
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content'    => 'required|string|max:1000',
            'title'      => 'nullable|string|max:255',
            'visibility' => 'required|in:public,university,private',
            'image'      => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id'    => auth()->id(),
            'title'      => $request->title,
            'content'    => $request->content,
            'visibility' => $request->visibility,
            'image'      => $imagePath,
        ]);

        return back()->with('success', '✅ Post publié avec succès');
    }

    /**
     * Update an existing post (owner or admin only via PostPolicy).
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'content'    => 'required|string|max:1000',
            'visibility' => 'required|in:public,university,private',
        ]);

        $post->update([
            'content'    => $request->content,
            'visibility' => $request->visibility,
        ]);

        return back()->with('success', '✅ Post modifié avec succès');
    }

    /**
     * Delete a post (owner or admin only via PostPolicy).
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image && !str_starts_with($post->image, 'http')) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return back()->with('success', '✅ Post supprimé avec succès');
    }

    /**
     * AJAX: Like a post.
     */
    public function like(Post $post)
    {
        $post->increment('likes_count');

        if ($post->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'type'    => 'new_like',
                'data'    => [
                    'message' => auth()->user()->name . ' a aimé votre post.',
                    'post_id' => $post->id,
                ],
            ]);
        }

        return response()->json([
            'success'     => true,
            'likes_count' => $post->likes_count,
        ]);
    }

    /**
     * Add a comment to a post.
     */
    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        if ($post->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'user_id' => $post->user_id,
                'type'    => 'new_comment',
                'data'    => [
                    'message' => auth()->user()->name . ' a commenté votre post.',
                    'post_id' => $post->id,
                ],
            ]);
        }

        return back()->with('success', '✅ Commentaire ajouté');
    }
}
