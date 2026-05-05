<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_like_on_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'likes_count' => 0
        ]);

        // Premier like (incrémente)
        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/like");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'liked' => true,
            'likes_count' => 1
        ]);
        $this->assertDatabaseHas('post_likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
        $this->assertEquals(1, $post->fresh()->likes_count);

        // Deuxième like (décrémente car toggle)
        $response = $this->actingAs($user)->postJson("/posts/{$post->id}/like");
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'liked' => false,
            'likes_count' => 0
        ]);
        $this->assertDatabaseMissing('post_likes', [
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);
        $this->assertEquals(0, $post->fresh()->likes_count);
    }
}
