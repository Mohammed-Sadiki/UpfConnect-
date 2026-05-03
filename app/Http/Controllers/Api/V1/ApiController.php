<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Event;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('uniconnect-mobile')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'data' => ['token' => $token, 'user' => $user],
                'message' => 'Connexion réussie'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Identifiants invalides'
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Déconnecté']);
    }

    public function users()
    {
        $users = User::with('profile')->paginate(15);
        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    public function userProfile($id)
    {
        $user = User::with(['profile', 'posts'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $user]);
    }

    public function posts()
    {
        $posts = Post::with(['user.profile', 'comments'])->latest()->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $posts->items(),
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    public function storePost(Request $request)
    {
        $request->validate(['content' => 'required|string']);
        $post = Post::create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
            'title' => $request->title,
            'visibility' => 'public'
        ]);
        return response()->json(['success' => true, 'data' => $post, 'message' => 'Post créé']);
    }

    public function postDetails($id)
    {
        $post = Post::with(['user.profile', 'comments.user'])->findOrFail($id);
        return response()->json(['success' => true, 'data' => $post]);
    }

    public function events()
    {
        $events = Event::with('creator')->where('event_date', '>=', now())->latest()->get();
        return response()->json(['success' => true, 'data' => $events]);
    }

    public function notifications(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->get();
        return response()->json(['success' => true, 'data' => $notifications]);
    }
}
