<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Event;
use App\Models\Message;
use App\Models\Connection;
use App\Models\Comment;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Stats basiques
        $stats = [
            'users_count' => User::count(),
            'posts_count' => Post::count(),
            'events_count' => Event::count(),
            'groups_count' => Group::count(),
            'active_students' => User::where('role', 'student')->where('is_active', true)->count(),
            'messages_count' => Message::count(),
            'connections_count' => Connection::where('status', 'accepted')->count(),
            'comments_count' => Comment::count(),
        ];

        // Inscriptions par mois (6 derniers mois)
        $userGrowth = User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $userGrowthLabels = $userGrowth->pluck('month')->map(fn($m) => Carbon::createFromFormat('Y-m', $m)->format('M Y'))->toArray();
        $userGrowthData = $userGrowth->pluck('count')->toArray();

        // Posts par semaine (4 dernières semaines)
        $postActivity = Post::select(
            DB::raw('YEARWEEK(created_at) as week'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', Carbon::now()->subWeeks(4))
        ->groupBy('week')
        ->orderBy('week')
        ->get();

        $postActivityLabels = [];
        $postActivityData = [];
        foreach ($postActivity as $item) {
            $postActivityLabels[] = 'Semaine ' . substr($item->week, -2);
            $postActivityData[] = $item->count;
        }

        // Répartition par rôle
        $roleDistribution = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();
        $roleLabels = $roleDistribution->pluck('role')->map(fn($r) => ucfirst($r))->toArray();
        $roleData = $roleDistribution->pluck('count')->toArray();

        // Activité des 7 derniers jours
        $weeklyActivity = [];
        $dailyPosts = [];
        $dailyMessages = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyActivity[] = $date->format('D');
            $dailyPosts[] = Post::whereDate('created_at', $date)->count();
            $dailyMessages[] = Message::whereDate('created_at', $date)->count();
        }

        // Top contributeurs
        $topContributors = User::select('users.id', 'users.name', 'users.avatar')
            ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
            ->selectRaw('COUNT(posts.id) as posts_count')
            ->groupBy('users.id', 'users.name', 'users.avatar')
            ->orderByDesc('posts_count')
            ->limit(5)
            ->get();

        // Dernières activités
        $recentActivity = collect()
            ->merge(Post::latest()->limit(5)->get()->map(fn($p) => [
                'type' => 'post',
                'user' => $p->user->name ?? 'Inconnu',
                'action' => 'a publié un post',
                'time' => $p->created_at,
            ]))
            ->merge(User::latest()->limit(5)->get()->map(fn($u) => [
                'type' => 'user',
                'user' => $u->name,
                'action' => 's\'est inscrit',
                'time' => $u->created_at,
            ]))
            ->sortByDesc('time')
            ->take(10);

        return view('admin.dashboard', compact(
            'stats',
            'userGrowthLabels',
            'userGrowthData',
            'postActivityLabels',
            'postActivityData',
            'roleLabels',
            'roleData',
            'weeklyActivity',
            'dailyPosts',
            'dailyMessages',
            'topContributors',
            'recentActivity'
        ));
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
