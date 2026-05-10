<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        $posts = Post::with(['user', 'comments.user', 'likedByUsers'])
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
            'content'    => 'nullable|string|max:1000',
            'title'      => 'nullable|string|max:255',
            'visibility' => 'required|in:public,university,private',
            'image'      => 'nullable|image|max:10240', // 10MB max
            'group_id'   => 'nullable|exists:groups,id',
        ]);

        // At least content or image must be present
        if (empty($request->content) && !$request->hasFile('image')) {
            return back()->with('error', 'Veuillez ajouter du texte ou une image au post.')->withInput();
        }

        // Vérifier si l'utilisateur peut poster dans le groupe
        if ($request->filled('group_id')) {
            $group = \App\Models\Group::find($request->group_id);
            if (!$group || !$group->isMember(auth()->user())) {
                return back()->with('error', 'Vous devez être membre du groupe pour publier.')->withInput();
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = ImageUploadService::upload(
                    $request->file('image'),
                    'posts',
                    1200,  // max width
                    800    // max height
                );
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de l\'upload de l\'image: ' . $e->getMessage());
            }
        }

        $post = Post::create([
            'user_id'    => auth()->id(),
            'group_id'   => $request->group_id,
            'title'      => $request->title,
            'content'    => $request->content ?? '', // Empty string if null
            'visibility' => $request->visibility,
            'image'      => $imagePath,
        ]);

        // Incrémenter le compteur de posts du groupe
        if ($request->filled('group_id')) {
            $group->increment('posts_count');
        }

        // Redirection vers le groupe si post dans un groupe
        if ($request->filled('group_id')) {
            return redirect()->route('groups.show', $request->group_id)
                ->with('success', '✅ Post publié dans le groupe avec succès');
        }

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

        $group = $post->group;

        if ($post->image && !str_starts_with($post->image, 'http')) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        // Décrémenter compteur de posts du groupe
        if ($group) {
            $group->decrement('posts_count');
        }

        return back()->with('success', '✅ Post supprimé avec succès');
    }

    /**
     * AJAX: Toggle le like d'un post (1 like max par utilisateur).
     */
    public function like(Post $post)
    {
        $user = auth()->user();

        return DB::transaction(function () use ($post, $user) {
            // Lock pour éviter les race conditions
            $post = Post::lockForUpdate()->find($post->id);

            // Vérifie si l'utilisateur a déjà liké ce post
            $alreadyLiked = $post->likedByUsers()->where('user_id', $user->id)->exists();

            if ($alreadyLiked) {
                // Unlike : on retire le like
                $post->likedByUsers()->detach($user->id);
                $liked = false;
            } else {
                // Like : on ajoute le like
                $post->likedByUsers()->attach($user->id);
                $liked = true;

                // Notification uniquement si c'est un nouveau like et pas son propre post
                if ($post->user_id !== $user->id) {
                    \App\Models\Notification::create([
                        'user_id' => $post->user_id,
                        'type'    => 'new_like',
                        'data'    => [
                            'message' => $user->name . ' a aimé votre post.',
                            'post_id' => $post->id,
                        ],
                    ]);
                }
            }

            // Recalculer le compteur exact (plus fiable que increment/decrement)
            $likesCount = $post->likedByUsers()->count();
            $post->update(['likes_count' => $likesCount]);

            return response()->json([
                'success'     => true,
                'liked'       => $liked,
                'likes_count' => $likesCount,
            ]);
        });
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
