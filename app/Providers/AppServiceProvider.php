<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();

                // Stats utilisateur
                $connectionsCount = $user->connectionsSent()
                    ->where('status', 'accepted')->count()
                    + $user->connectionsReceived()
                        ->where('status', 'accepted')->count();

                $postsCount = $user->posts()->count();

                $unreadNotificationsCount = $user->notifications()
                    ->whereNull('read_at')
                    ->count();

                // "À la une" - Événements à venir
                $upcomingEvents = \App\Models\Event::withCount('registrations')
                    ->where('event_date', '>=', now())
                    ->orderBy('event_date')
                    ->take(3)
                    ->get();

                // Posts populaires (plus de likes)
                $trendingPosts = \App\Models\Post::with('user')
                    ->where('visibility', 'public')
                    ->where('likes_count', '>', 0)
                    ->orderBy('likes_count', 'desc')
                    ->take(3)
                    ->get();

                $view->with([
                    'userConnectionsCount' => $connectionsCount,
                    'userPostsCount' => $postsCount,
                    'unreadNotificationsCount' => $unreadNotificationsCount,
                    'sidebarUpcomingEvents' => $upcomingEvents,
                    'sidebarTrendingPosts' => $trendingPosts,
                ]);
            }
        });
    }
}
