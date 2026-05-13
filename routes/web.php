<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('home');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PostController::class, 'feed'])->name('dashboard');

    // Posts & Comments
    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comments', [PostController::class, 'comment'])->name('posts.comment');

    // Profiles
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Connections
    Route::get('/connections', [ConnectionController::class, 'index'])->name('connections.index');
    Route::post('/connections/{user}', [ConnectionController::class, 'sendRequest'])->name('connections.request');
    Route::post('/connections/{connection}/accept', [ConnectionController::class, 'accept'])->name('connections.accept');
    Route::post('/connections/{connection}/reject', [ConnectionController::class, 'reject'])->name('connections.reject');
    Route::delete('/connections/{connection}', [ConnectionController::class, 'destroy'])->name('connections.destroy');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::delete('/messages/{message}/delete', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Events
    Route::resource('events', EventController::class);
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/unregister', [EventController::class, 'unregister'])->name('events.unregister');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAndRedirect'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/suggestions/profiles', [SearchController::class, 'profileSuggestions'])->name('suggestions.profiles');

    // Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/suggestions', [GroupController::class, 'suggestions'])->name('groups.suggestions');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::get('/groups/{group}/members', [GroupController::class, 'members'])->name('groups.members');
    Route::post('/groups/{group}/members/{membership}/approve', [GroupController::class, 'approveMember'])->name('groups.members.approve');
    Route::post('/groups/{group}/members/{membership}/reject', [GroupController::class, 'rejectMember'])->name('groups.members.reject');
    Route::patch('/groups/{group}/members/{membership}/role', [GroupController::class, 'updateMemberRole'])->name('groups.members.role');
    Route::delete('/groups/{group}/members/{membership}', [GroupController::class, 'removeMember'])->name('groups.members.remove');

    // Admin Panel (Protected by role middleware)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.updateRole');
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggleStatus');
    });
});

require __DIR__.'/auth.php';
