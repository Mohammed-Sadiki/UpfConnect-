<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Event;
use App\Models\Connection;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Tests complets de l'application UPFConnect
 * 
 * Coverage:
 * - Authentification (Login/Register)
 * - Posts (CRUD, Likes, Comments)
 * - Profil (View, Edit, Delete)
 * - Connexions (Request, Accept, Reject)
 * - Messages (Send, Read)
 * - Événements (Create, Register)
 * - Notifications
 * - Recherche
 * - Admin
 */
class CompleteApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    // ============================================================================
    // TESTS: AUTHENTICATION
    // ============================================================================

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@upfconnect.edu',
            'password' => bcrypt('password'),
            'is_active' => true
        ]);

        $response = $this->post('/login', [
            'email' => 'test@upfconnect.edu',
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'test@upfconnect.edu',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@upfconnect.edu',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function user_can_register_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Nouvel Utilisateur',
            'email' => 'nouveau@upfconnect.edu',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'department' => 'Informatique',
            'study_year' => '3',
            'role' => 'student'
        ]);

        $response->assertRedirect('/verify-email');
        $this->assertDatabaseHas('users', [
            'email' => 'nouveau@upfconnect.edu',
            'name' => 'Nouvel Utilisateur'
        ]);
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        $user = User::factory()->create([
            'email' => 'inactive@upfconnect.edu',
            'password' => bcrypt('password'),
            'is_active' => false
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@upfconnect.edu',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors();
    }

    // ============================================================================
    // TESTS: DASHBOARD & POSTS
    // ============================================================================

    /** @test */
    public function authenticated_user_can_view_dashboard()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    /** @test */
    public function user_can_create_post()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Mon premier post de test',
            'title' => 'Titre optionnel',
            'visibility' => 'public'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'content' => 'Mon premier post de test',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_can_create_post_with_image()
    {
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('post.jpg', 800, 600)->size(1000);
        
        $response = $this->actingAs($user)->post('/posts', [
            'content' => 'Post avec image',
            'visibility' => 'public',
            'image' => $image
        ]);
        
        $response->assertRedirect();
        Storage::disk('public')->assertExists('posts/' . $image->hashName());
    }

    /** @test */
    public function user_can_update_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->patch("/posts/{$post->id}", [
            'content' => 'Contenu modifié',
            'visibility' => 'private'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'content' => 'Contenu modifié',
            'visibility' => 'private'
        ]);
    }

    /** @test */
    public function user_cannot_update_others_post()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);
        
        $response = $this->actingAs($user)->patch("/posts/{$post->id}", [
            'content' => 'Tentative de modification',
            'visibility' => 'public'
        ]);
        
        $response->assertForbidden();
    }

    /** @test */
    public function user_can_delete_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->delete("/posts/{$post->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    public function user_can_like_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        $response = $this->actingAs($user)->post("/posts/{$post->id}/like");
        
        $response->assertJson(['success' => true, 'liked' => true]);
        $this->assertDatabaseHas('post_likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_can_unlike_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $post->likedByUsers()->attach($user->id);
        
        $response = $this->actingAs($user)->post("/posts/{$post->id}/like");
        
        $response->assertJson(['success' => true, 'liked' => false]);
        $this->assertDatabaseMissing('post_likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function like_creates_notification_for_post_owner()
    {
        $postOwner = User::factory()->create();
        $liker = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);
        
        $this->actingAs($liker)->post("/posts/{$post->id}/like");
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $postOwner->id,
            'type' => 'new_like'
        ]);
    }

    /** @test */
    public function user_can_comment_on_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        $response = $this->actingAs($user)->post("/posts/{$post->id}/comments", [
            'content' => 'Super post !'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Super post !'
        ]);
    }

    /** @test */
    public function comment_creates_notification_for_post_owner()
    {
        $postOwner = User::factory()->create();
        $commenter = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $postOwner->id]);
        
        $this->actingAs($commenter)->post("/posts/{$post->id}/comments", [
            'content' => 'Commentaire test'
        ]);
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $postOwner->id,
            'type' => 'new_comment'
        ]);
    }

    // ============================================================================
    // TESTS: PROFILE
    // ============================================================================

    /** @test */
    public function user_can_view_own_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get("/profile/{$user->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('profile.show');
    }

    /** @test */
    public function user_can_view_others_profile()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $response = $this->actingAs($user)->get("/profile/{$otherUser->id}");
        
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_update_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Nouveau Nom',
            'bio' => 'Nouvelle bio',
            'linkedin_url' => 'https://linkedin.com/in/test',
            'github_url' => 'https://github.com/test',
            'skills' => 'PHP, Laravel, Vue.js',
            'interests' => 'Web Dev, AI'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nouveau Nom',
            'bio' => 'Nouvelle bio'
        ]);
    }

    /** @test */
    public function user_can_upload_avatar()
    {
        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(500);
        
        $response = $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'avatar' => $avatar
        ]);
        
        $response->assertRedirect();
        Storage::disk('public')->assertExists('avatars/' . $avatar->hashName());
    }

    /** @test */
    public function user_can_delete_account()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);
        
        $response = $this->actingAs($user)->delete('/profile', [
            'password' => 'password'
        ]);
        
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertGuest();
    }

    // ============================================================================
    // TESTS: CONNECTIONS
    // ============================================================================

    /** @test */
    public function user_can_send_connection_request()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        
        $response = $this->actingAs($sender)->post("/connections/{$receiver->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('connections', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function connection_request_creates_notification()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        
        $this->actingAs($sender)->post("/connections/{$receiver->id}");
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $receiver->id,
            'type' => 'connection_request'
        ]);
    }

    /** @test */
    public function user_cannot_send_request_to_self()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post("/connections/{$user->id}");
        
        $response->assertSessionHas('error');
    }

    /** @test */
    public function receiver_can_accept_connection()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $connection = Connection::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending'
        ]);
        
        $response = $this->actingAs($receiver)->post("/connections/{$connection->id}/accept");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('connections', [
            'id' => $connection->id,
            'status' => 'accepted'
        ]);
    }

    /** @test */
    public function accept_connection_creates_notification()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $connection = Connection::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending'
        ]);
        
        $this->actingAs($receiver)->post("/connections/{$connection->id}/accept");
        
        $this->assertDatabaseHas('notifications', [
            'user_id' => $sender->id,
            'type' => 'connection_accepted'
        ]);
    }

    /** @test */
    public function receiver_can_reject_connection()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        $connection = Connection::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'status' => 'pending'
        ]);
        
        $response = $this->actingAs($receiver)->post("/connections/{$connection->id}/reject");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('connections', [
            'id' => $connection->id,
            'status' => 'rejected'
        ]);
    }

    /** @test */
    public function user_can_remove_connection()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $connection = Connection::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'status' => 'accepted'
        ]);
        
        $response = $this->actingAs($user1)->delete("/connections/{$connection->id}");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('connections', ['id' => $connection->id]);
    }

    // ============================================================================
    // TESTS: MESSAGES
    // ============================================================================

    /** @test */
    public function user_can_view_messages_list()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/messages');
        
        $response->assertStatus(200);
        $response->assertViewIs('messages.index');
    }

    /** @test */
    public function user_can_view_conversation()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $response = $this->actingAs($user)->get("/messages/{$otherUser->id}");
        
        $response->assertStatus(200);
        $response->assertViewIs('messages.show');
    }

    /** @test */
    public function user_can_send_message()
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();
        
        $response = $this->actingAs($sender)->post("/messages/{$receiver->id}", [
            'body' => 'Bonjour, comment ça va ?'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'body' => 'Bonjour, comment ça va ?'
        ]);
    }

    /** @test */
    public function viewing_conversation_marks_messages_as_read()
    {
        $user = User::factory()->create();
        $sender = User::factory()->create();
        
        Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $user->id,
            'body' => 'Message non lu',
            'read_at' => null
        ]);
        
        $this->actingAs($user)->get("/messages/{$sender->id}");
        
        $this->assertDatabaseMissing('messages', [
            'receiver_id' => $user->id,
            'read_at' => null
        ]);
    }

    // ============================================================================
    // TESTS: EVENTS
    // ============================================================================

    /** @test */
    public function user_can_view_events_list()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/events');
        
        $response->assertStatus(200);
        $response->assertViewIs('events.index');
    }

    /** @test */
    public function admin_can_create_event()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/events', [
            'title' => 'Conférence Tech',
            'description' => 'Description de la conférence',
            'location' => 'Amphithéâtre A',
            'event_date' => now()->addDays(7)->format('Y-m-d H:i:s')
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title' => 'Conférence Tech'
        ]);
    }

    /** @test */
    public function student_cannot_create_event()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->post('/events', [
            'title' => 'Événement non autorisé',
            'description' => 'Description',
            'location' => 'Lieu',
            'event_date' => now()->addDays(7)
        ]);
        
        $response->assertForbidden();
    }

    /** @test */
    public function user_can_register_for_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'event_date' => now()->addDays(7)
        ]);
        
        $response = $this->actingAs($user)->post("/events/{$event->id}/register");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_cannot_register_twice_for_same_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'event_date' => now()->addDays(7)
        ]);
        
        $this->actingAs($user)->post("/events/{$event->id}/register");
        $this->actingAs($user)->post("/events/{$event->id}/register");
        
        $count = \App\Models\EventRegistration::where([
            'event_id' => $event->id,
            'user_id' => $user->id
        ])->count();
        
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function user_can_unregister_from_event()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'event_date' => now()->addDays(7)
        ]);
        
        \App\Models\EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id
        ]);
        
        $response = $this->actingAs($user)->post("/events/{$event->id}/unregister");
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id
        ]);
    }

    // ============================================================================
    // TESTS: NOTIFICATIONS
    // ============================================================================

    /** @test */
    public function user_can_view_notifications()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'data' => ['message' => 'Test notification']
        ]);
        
        $response = $this->actingAs($user)->get('/notifications');
        
        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
    }

    /** @test */
    public function user_can_mark_all_notifications_as_read()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'data' => ['message' => 'Test'],
            'read_at' => null
        ]);
        
        $response = $this->actingAs($user)->post('/notifications/read-all');
        
        $response->assertRedirect();
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $user->id,
            'read_at' => null
        ]);
    }

    /** @test */
    public function view_composer_shares_notification_count()
    {
        $user = User::factory()->create();
        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'data' => ['message' => 'Test'],
            'read_at' => null
        ]);
        
        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertViewHas('unreadNotificationsCount', 1);
    }

    // ============================================================================
    // TESTS: SEARCH
    // ============================================================================

    /** @test */
    public function user_can_search_users()
    {
        $user = User::factory()->create(['name' => 'Jean Dupont']);
        $searcher = User::factory()->create();
        
        $response = $this->actingAs($searcher)->get('/search?q=Jean');
        
        $response->assertStatus(200);
        $response->assertViewHas('users', function ($users) use ($user) {
            return $users->contains('id', $user->id);
        });
    }

    /** @test */
    public function user_can_search_posts()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'content' => 'Contenu spécifique pour recherche',
            'visibility' => 'public'
        ]);
        $searcher = User::factory()->create();
        
        $response = $this->actingAs($searcher)->get('/search?q=spécifique');
        
        $response->assertStatus(200);
        $response->assertViewHas('posts', function ($posts) use ($post) {
            return $posts->contains('id', $post->id);
        });
    }

    /** @test */
    public function search_requires_minimum_two_characters()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/search?q=a');
        
        $response->assertSessionHas('error');
    }

    // ============================================================================
    // TESTS: ADMIN
    // ============================================================================

    /** @test */
    public function admin_can_view_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(5)->create();
        Post::factory()->count(3)->create();
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewHas('stats');
    }

    /** @test */
    public function non_admin_cannot_access_admin_dashboard()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($student)->get('/admin/dashboard');
        
        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_view_users_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(10)->create();
        
        $response = $this->actingAs($admin)->get('/admin/users');
        
        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    /** @test */
    public function admin_can_update_user_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'student']);
        
        $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/role", [
            'role' => 'teacher'
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'teacher'
        ]);
    }

    /** @test */
    public function admin_can_toggle_user_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['is_active' => true]);
        
        $response = $this->actingAs($admin)->patch("/admin/users/{$user->id}/toggle-status");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false
        ]);
    }

    // ============================================================================
    // TESTS: API
    // ============================================================================

    /** @test */
    public function api_user_can_login_and_get_token()
    {
        $user = User::factory()->create([
            'email' => 'api@test.com',
            'password' => bcrypt('password')
        ]);
        
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'api@test.com',
            'password' => 'password'
        ]);
        
        $response->assertOk();
        $response->assertJsonStructure(['success', 'data.token', 'data.user']);
    }

    /** @test */
    public function api_user_can_get_posts_with_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/posts');
        
        $response->assertOk();
        $response->assertJsonStructure(['success', 'data', 'pagination']);
    }

    /** @test */
    public function api_returns_error_without_token()
    {
        $response = $this->getJson('/api/v1/posts');
        
        $response->assertUnauthorized();
    }

    /** @test */
    public function api_user_can_get_events()
    {
        $user = User::factory()->create();
        Event::factory()->count(3)->create(['event_date' => now()->addDays(7)]);
        $token = $user->createToken('test')->plainTextToken;
        
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/events');
        
        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }
}
