<?php

namespace Tests\Feature\Public;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected User $author;
    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->author = User::factory()->create([
            'role' => 'admin'
        ]);

        $this->post = Post::factory()->create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'author_id' => $this->author->id
        ]);
    }

    public function test_guest_cannot_submit_comment_without_name_or_email(): void
    {
        $response = $this->post(route('berita.comments.store', $this->post->slug), [
            'content' => 'This is a test comment.'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_guest_can_submit_comment_successfully(): void
    {
        $response = $this->post(route('berita.comments.store', $this->post->slug), [
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'content' => 'This is a comment by a guest.'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'content' => 'This is a comment by a guest.',
            'parent_id' => null
        ]);
    }

    public function test_authenticated_user_can_submit_comment_without_guest_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('berita.comments.store', $this->post->slug), [
            'content' => 'This is a comment by a user.'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'user_id' => $user->id,
            'content' => 'This is a comment by a user.',
            'name' => null
        ]);
    }

    public function test_user_can_reply_to_another_comment(): void
    {
        $parentComment = Comment::create([
            'post_id' => $this->post->id,
            'name' => 'Original Poster',
            'email' => 'original@example.com',
            'content' => 'Original comment content',
            'status' => 'approved'
        ]);

        $response = $this->post(route('berita.comments.store', $this->post->slug), [
            'name' => 'Replier',
            'email' => 'replier@example.com',
            'content' => 'This is a reply.',
            'parent_id' => $parentComment->id
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('comments', [
            'post_id' => $this->post->id,
            'parent_id' => $parentComment->id,
            'name' => 'Replier',
            'content' => 'This is a reply.'
        ]);
    }

    public function test_user_can_upvote_comment_once(): void
    {
        $comment = Comment::create([
            'post_id' => $this->post->id,
            'name' => 'User',
            'email' => 'user@example.com',
            'content' => 'Nice article',
            'status' => 'approved',
            'upvotes' => 5
        ]);

        $response = $this->post(route('comments.upvote', $comment->id));
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('upvotes', 6);

        $this->assertEquals(6, $comment->fresh()->upvotes);

        // Second upvote should fail
        $secondResponse = $this->post(route('comments.upvote', $comment->id));
        $secondResponse->assertStatus(422);
    }
}
