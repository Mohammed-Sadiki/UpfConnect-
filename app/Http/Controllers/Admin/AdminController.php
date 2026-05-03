<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Event;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'posts_count' => Post::count(),
            'events_count' => Event::count(),
            'active_students' => User::where('role', 'student')->where('is_active', true)->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::with('profile')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,teacher,student']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Rôle mis à jour.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte a été $status.");
    }
}
